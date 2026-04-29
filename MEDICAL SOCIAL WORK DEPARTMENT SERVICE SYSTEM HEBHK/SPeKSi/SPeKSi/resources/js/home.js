// Replace the confirmLogout function with Laravel's logout
window.confirmGoLogin = function (url) {
    if (confirm("Adakah anda pasti mahu kembali ke halaman Login?")) {
        window.location.href = url;
    }
};

window.confirmLogout = function () {
    if (confirm("Adakah anda pasti mahu log keluar?")) {
        document.getElementById('logout-form').submit();
    }
};

window.goToHistory = function (kp) {
    const url = window.patientsHistoryUrl + '?no_kp=' + encodeURIComponent(kp);
    window.location.href = url;
};

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.go-login').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            let url = this.dataset.url;
            if (confirm("Adakah anda pasti mahu kembali ke halaman Login?")) {
                window.location.href = url;
            }
        });
    });
});

/* ===========================
   Rekod Pesakit
   =========================== */
const noKPInput = document.getElementById('noKP');
const recordDisplay = document.getElementById('recordDisplay');
const recordDetails = document.getElementById('recordDetails');
const recordButtons = document.getElementById('recordButtons');
const clearBtn = document.getElementById('clearBtn');

noKPInput.addEventListener('input', function () {
    if (this.value.length === 12) checkRecord(this.value);
    clearBtn.style.display = this.value ? 'block' : 'none';
});

clearBtn.addEventListener('click', function () {
    hideRecordDisplay();
});


function checkRecord(kpNumber) {
    fetch(window.patientsSearchUrl + "?no_kp=" + kpNumber)
        .then(response => response.json())
        .then(result => {
            if (result.exists === true) {
                const tarikh = result.data.date ? result.data.date : '-';

                recordDetails.innerHTML = `
                    <p><strong>NAMA PEMOHON :</strong> ${result.data.name}</p>
                    <p><strong>NO.KP :</strong> ${result.data.no_kp}</p>
                    <p><strong>TARIKH PERMOHONAN :</strong> ${tarikh}</p>
                    <hr>`;

                recordButtons.innerHTML = `
                    <button class="option-btn history" onclick="goToHistory('${kpNumber}')">SEJARAH</button>
                    <button class="option-btn new-application" onclick="newApplication()">DAFTAR PERMOHONAN BARU</button>
                    <button class="option-btn cancel" onclick="hideRecordDisplay()">BATAL</button>
                    `;
            } else {
                recordDetails.innerHTML = `
                    <div class="no-record">REKOD TIDAK WUJUD!</div>
                    <hr>`;

                recordButtons.innerHTML = `
                    <button class="option-btn new-application" onclick="newApplication()">DAFTAR PERMOHONAN BARU</button>`
            }

            recordDisplay.style.display = 'block';
        })
        .catch(err => {
            console.error("Error:", err);

            recordDetails.innerHTML = `
                <div class="no-record">Ralat semasa carian.</div>
                <hr>`;

            recordButtons.innerHTML = `
                <button class="option-btn cancel" onclick="hideRecordDisplay()">BATAL</button>`;

            recordDisplay.style.display = 'block';
        });
}

// ✅ BATAL + ❌ sekarang guna function sama
function hideRecordDisplay() {
    recordDisplay.style.display = 'none';
    noKPInput.value = '';
    clearBtn.style.display = 'none';
}

function newApplication() {
    let kp = noKPInput.value;
    window.location.href = window.patientsCreateUrl + "?kp=" + encodeURIComponent(kp);
}

/* ===========================
   Calendar + Reminder (DB only, read-only)
   =========================== */
document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    window.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'ms',
        editable: false,   // ❌ user tak boleh ubah
        selectable: false, // ❌ user tak boleh klik tambah
        buttonText: {
            today: 'Hari Ini'
        },
        events: function (fetchInfo, successCallback, failureCallback) {
            fetch(window.appointmentsUpcomingUrl)
                .then(res => res.json())
                .then(data => {
                    const events = data.map(app => ({
                        title: app.patient_name,
                        start: app.date
                    }));
                    successCallback(events);
                })
                .catch(err => {
                    console.error("Error loading calendar events:", err);
                    failureCallback(err);
                });
        },
        eventClick: function (info) {
            alert(`Pesakit: ${info.event.title}\nTarikh: ${info.event.start.toLocaleDateString('ms-MY')}`);
        }
    });

    calendar.render();
    updateReminderList();
});

/* ===========================
   Reminder list
   =========================== */
function updateReminderList() {
    const reminderList = document.getElementById('reminder-list');
    reminderList.innerHTML = '<div class="loading">Memuatkan...</div>';

    fetch(window.appointmentsUpcomingUrl)
        .then(res => res.json())
        .then(data => {
            reminderList.innerHTML = '';

            if (data.length === 0) {
                reminderList.innerHTML = '<div class="no-appointments">Tiada temujanji akan datang.</div>';
                return;
            }

            data.forEach(app => {
                const dateObj = new Date(app.date);
                const formatted = dateObj.toLocaleDateString('ms-MY', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });

                const item = document.createElement('div');
                item.className = 'appointment-item';
                item.innerHTML = `
                    <div><strong>${app.patient_name}</strong></div>
                    <div style="font-size:0.9em;color:#666;">${formatted}</div>
                `;
                reminderList.appendChild(item);
            });
        })
        .catch(err => {
            console.error("Error loading appointments:", err);
            reminderList.innerHTML = '<div class="no-appointments">Ralat memuatkan temujanji.</div>';
        });
}



