self.addEventListener("install", (event) => {
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(self.clients.claim());
});

// Menerima push event
self.addEventListener("push", function (event) {
    let data = {};
    try {
        if (event.data) {
            data = event.data.json();
        }
    } catch (e) {
        console.error("Push event data error:", e);
    }

    const title = data.title || "Notifikasi";
    const options = {
        body: data.body || "Anda mendapat pesan baru.",
        icon: data.icon || "icons/icon-192.png",
        badge: data.badge || "icons/icon-512.png/badge.png",
        // tidak perlu data.url kalau tidak ada tujuan klik
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Menangani klik notifikasi (hanya menutup notif, tidak membuka halaman)
self.addEventListener("notificationclick", function (event) {
    event.notification.close();
    // Tidak ada action lain, jadi notif hanya hilang setelah diklik
});
