<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyTarget;
use App\Models\SurveyResult;
use App\Models\SurveyQuestion;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    /**
     * 📋 List semua survei
     */
    public function index()
    {
        $surveys = Survey::latest()->get();

        return view('surveys.index', compact('surveys'));
    }

    /**
     * 📝 Tampilkan form survei
     */
    public function form($id)
    {
        $survey = Survey::with('questions')->findOrFail($id);

        // Ambil pegawai yang ditargetkan
        $targets = SurveyTarget::with('employee')
            ->where('survey_id', $id)
            ->get();

        // Kalau tidak ada target
        if ($targets->isEmpty()) {
            return redirect()->route('surveys.index')
                ->with('error', 'Tidak ada pegawai untuk dinilai');
        }

        return view('surveys.form', compact('survey', 'targets'));
    }

    /**
     * 💾 Simpan hasil survei
     */
    public function submit(Request $request, $id)
    {
        // Validasi dasar
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'answers' => 'required|array'
        ]);

        // Cek apakah pegawai termasuk target survei
        $isTarget = SurveyTarget::where('survey_id', $id)
            ->where('employee_id', $request->employee_id)
            ->exists();

        if (!$isTarget) {
            return back()->with('error', 'Pegawai tidak termasuk dalam survei ini');
        }

        // Cek apakah sudah pernah dinilai (biar tidak double)
        $already = SurveyResult::where('survey_id', $id)
            ->where('employee_id', $request->employee_id)
            ->exists();

        if ($already) {
            return back()->with('error', 'Pegawai ini sudah dinilai');
        }

        // Simpan header hasil survei
        $result = SurveyResult::create([
            'survey_id' => $id,
            'employee_id' => $request->employee_id,
            'evaluator_id' => auth()->user()->employee_id ?? null
        ]);

        // Simpan detail jawaban
        foreach ($request->answers as $questionId => $score) {

            // Validasi skor 1–5
            if ($score < 1 || $score > 5) {
                continue;
            }

            $result->details()->create([
                'question_id' => $questionId,
                'score' => $score,
                'answer' => $request->comments[$questionId] ?? null
            ]);
        }

        return redirect()->route('surveys.index')
            ->with('success', 'Survei berhasil disimpan');
    }

    public function formByType($type)
    {
        // validasi type
        if (!in_array($type, ['pns', 'ppnpn'])) {
            abort(404);
        }

        // ambil survei berdasarkan type
        $survey = Survey::with('questions')
            ->where('type', $type)
            ->latest()
            ->first();

        if (!$survey) {
            return back()->with('error', 'Survei tidak ditemukan');
        }

        // ambil target pegawai
        $targets = SurveyTarget::with('employee')
            ->where('survey_id', $survey->id)
            ->get();

        if ($targets->isEmpty()) {
            return back()->with('error', 'Tidak ada pegawai untuk dinilai');
        }

        // ambil yang sudah dinilai
        $evaluatedEmployeeIds = SurveyResult::where('survey_id', $survey->id)
            ->pluck('employee_id')
            ->toArray();

        // ambil pegawai berikutnya
        $currentTarget = $targets
            ->whereNotIn('employee_id', $evaluatedEmployeeIds)
            ->first();

        if (!$currentTarget) {
            return view('surveys.finished');
        }

        return view('surveys.form', compact('survey', 'currentTarget', 'type'));
    }

    public function submitByType(Request $request, $type)
    {
        if (!in_array($type, ['pns', 'ppnpn'])) {
            abort(404);
        }

        $survey = Survey::where('type', $type)->latest()->first();

        if (!$survey) {
            return back()->with('error', 'Survei tidak ditemukan');
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'answers' => 'required|array',
            'answers.*' => 'required|integer|min:1|max:5'
        ]);

        // cek double input
        $already = SurveyResult::where('survey_id', $survey->id)
            ->where('employee_id', $request->employee_id)
            ->exists();

        if ($already) {
            return back()->with('error', 'Pegawai sudah dinilai');
        }

        // simpan
        $result = SurveyResult::create([
            'survey_id' => $survey->id,
            'employee_id' => $request->employee_id,
            'evaluator_id' => null // karena public
        ]);

        foreach ($request->answers as $questionId => $score) {
            $result->details()->create([
                'question_id' => $questionId,
                'score' => $score,
                'answer' => $request->comments[$questionId] ?? null
            ]);
        }

        // 🔥 next pegawai (tanpa ID)
        return redirect()->route('surveys.form.type', $type)
            ->with('success', 'Berhasil, lanjut ke pegawai berikutnya');
    }

    public function create()
    {
        $employees = Employee::all();
        $surveys = Survey::with('targets.employee')->latest()->get();

        return view('surveys.create', compact('employees', 'surveys'));
    }


    public function storeSurvey(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required|in:pns,ppnpn'
        ]);

        $survey = Survey::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type
        ]);

        return back()->with('success', 'Survey berhasil dibuat');
    }


    public function storeTarget(Request $request)
    {
        $request->validate([
            'survey_id' => 'required',
            'employees' => 'required|array'
        ]);

        foreach ($request->employees as $empId) {
            SurveyTarget::firstOrCreate([
                'survey_id' => $request->survey_id,
                'employee_id' => $empId,
            ]);
        }

        return back()->with('success', 'Target berhasil disimpan');
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'questions' => 'required|array'
        ]);

        foreach ($request->questions as $q) {
            SurveyQuestion::create([
                'survey_id' => $request->survey_id,
                'question' => $q
            ]);
        }

        return back()->with('success', 'Pertanyaan berhasil ditambahkan');
    }


    /**
     * 📊 Tampilkan hasil survei
     */
    public function result($id)
    {
        $survey = Survey::findOrFail($id);

        $results = SurveyResult::with(['employee', 'details'])
            ->where('survey_id', $id)
            ->get();

        return view('surveys.result', compact('survey', 'results'));
    }



    public function updateSurvey(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required'
        ]);

        $survey = Survey::findOrFail($id);

        $survey->update([
            'title' => $request->title,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Survey berhasil diupdate');
    }

    public function updateTarget(Request $request, $id)
    {
        $request->validate([
            'employees' => 'required|array'
        ]);

        // hapus semua target lama
        SurveyTarget::where('survey_id', $id)->delete();

        // insert ulang
        foreach ($request->employees as $empId) {
            SurveyTarget::create([
                'survey_id' => $id,
                'employee_id' => $empId
            ]);
        }

        return back()->with('success', 'Target pegawai berhasil diupdate');
    }

    public function destroyQuestion($id)
    {
        $question = SurveyQuestion::findOrFail($id);
        $question->delete();

        return back()->with('success', 'Pertanyaan berhasil dihapus');
    }

    public function destroyTarget($id)
    {
        $survey = Survey::findOrFail($id);

        // hapus juga target pegawai (biar bersih)
        $survey->targets()->delete();

        $survey->delete();

        return back()->with('success', 'Survey berhasil dihapus');
    }
}
