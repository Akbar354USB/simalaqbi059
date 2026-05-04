<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyResult;
use App\Models\SurveyResultDetail;
use App\Models\SurveyTarget;
// use DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class PublicSurveyController extends Controller
{
    public function pns()
    {
        return $this->loadSurvey('pns');
    }

    public function ppnpn()
    {
        return $this->loadSurvey('ppnpn');
    }

    private function loadSurvey($type)
    {
        $survey = Survey::with('questions')
            ->where('type', $type)
            ->latest()
            ->first();

        if (!$survey) {
            abort(404);
        }

        $targets = SurveyTarget::with('employee')
            ->where('survey_id', $survey->id)
            ->get();

        return view('surveys.public_survey', compact('survey', 'targets', 'type'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'survey_id' => 'required',
            'answers' => 'required|array'
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->answers as $employeeId => $questions) {

                $result = SurveyResult::firstOrCreate([
                    'survey_id' => $request->survey_id,
                    'employee_id' => $employeeId
                ]);

                foreach ($questions as $questionId => $score) {

                    SurveyResultDetail::updateOrCreate(
                        [
                            'survey_result_id' => $result->id,
                            'question_id' => $questionId
                        ],
                        [
                            'score' => $score,
                            'answer' => null
                        ]
                    );
                }
            }

            DB::commit();

            return back()->with('success', 'Terima kasih, survey berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan data');
        }
    }

    public function index()
    {
        // ambil survey terbaru per tipe
        $surveyPns = Survey::where('type', 'pns')->latest()->first();
        $surveyPpnpn = Survey::where('type', 'ppnpn')->latest()->first();

        // function ambil hasil
        // $getResult = function ($survey) {
        //     if (!$survey) return collect();

        //     return DB::table('survey_results')
        //         ->join('survey_result_details', 'survey_results.id', '=', 'survey_result_details.survey_result_id')
        //         ->join('employees', 'survey_results.employee_id', '=', 'employees.id')
        //         ->where('survey_results.survey_id', $survey->id)
        //         ->select(
        //             'employees.employee_name',
        //             'employees.id',
        //             DB::raw('AVG(survey_result_details.score) as avg_score'),
        //             DB::raw('COUNT(survey_result_details.id) as total')
        //         )
        //         ->groupBy('employees.id', 'employees.employee_name')
        //         ->orderByDesc('avg_score')
        //         ->get();
        // };

        $getResult = function ($survey) {
            if (!$survey) return collect();

            return DB::table('survey_results')
                ->join('survey_result_details', 'survey_results.id', '=', 'survey_result_details.survey_result_id')
                ->join('employees', 'survey_results.employee_id', '=', 'employees.id')
                ->where('survey_results.survey_id', $survey->id)
                ->select(
                    'employees.employee_name',
                    'employees.id',

                    // 🔥 total skor
                    DB::raw('SUM(survey_result_details.score) as total_score'),

                    // 🔥 jumlah jawaban
                    DB::raw('COUNT(survey_result_details.id) as total_question'),
                    DB::raw('COUNT(survey_result_details.id) as total'),

                    // 🔥 persentase
                    DB::raw('
                (SUM(survey_result_details.score) / (COUNT(survey_result_details.id) * 5)) * 100 
                as percentage_score
            ')
                )
                ->groupBy('employees.id', 'employees.employee_name')
                ->orderByDesc('percentage_score') // 🔥 ranking pakai persen
                ->get();
        };

        $resultsPns = $getResult($surveyPns);
        $resultsPpnpn = $getResult($surveyPpnpn);

        return view('surveys.survey_result_all', compact(
            'surveyPns',
            'surveyPpnpn',
            'resultsPns',
            'resultsPpnpn'
        ));
    }
}
