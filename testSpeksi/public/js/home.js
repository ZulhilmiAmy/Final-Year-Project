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

let allAppointments = [];
let selectedPegawai = null;
let pegawaiColors = {};

document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    window.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'ms',
        editable: false,
        selectable: false,
        buttonText: { today: 'Hari Ini' },
        eventClick: function (info) {
            alert(
                `Pesakit: ${info.event.title}\n` +
                `Tarikh: ${info.event.start.toLocaleDateString('ms-MY')}\n` +
                `Pegawai Kes: ${info.event.extendedProps.pegawai}`
            );
        }
    });

    calendar.render();
    fetchAppointmentsAndInit();
});

function fetchAppointmentsAndInit() {
    fetch(window.appointmentsUpcomingUrl)
        .then(res => res.json())
        .then(data => {
            allAppointments = data;

            // bina warna ikut pegawai
            buildPegawaiColors(allAppointments);

            // buat butang pegawai
            generatePegawaiButtons(allAppointments);

            // load semua events
            loadEventsToCalendar(allAppointments);

            // update reminder
            updateReminderList(allAppointments);
        })
        .catch(err => console.error(err));
}

function buildPegawaiColors(data) {
    pegawaiColors = {};
    let colors = ['#28a745', '#007bff', '#fd7e14', '#6f42c1', '#e83e8c'];
    let idx = 0;

    data.forEach(app => {
        const pegawai = app.pegawai_kes || 'Tidak Ditetapkan';
        if (!pegawaiColors[pegawai]) {
            pegawaiColors[pegawai] = app.color || colors[idx % colors.length];
            idx++;
        }
    });
}

function generatePegawaiButtons(data) {
    const container = document.getElementById('pegawai-buttons');
    container.innerHTML = '';

    const pegawaiList = [...new Set(data.map(app => app.pegawai_kes || 'Tidak Ditetapkan'))];

    pegawaiList.forEach(pg => {
        const btn = document.createElement('button');
        btn.className = 'pegawai-btn';
        btn.innerText = pg;
        btn.style.backgroundColor = pegawaiColors[pg]; // warna ikut pegawai
        // ❌ buang btn.style.color
        btn.onclick = () => filterByPegawai(pg);
        container.appendChild(btn);
    });

    const resetBtn = document.createElement('button');
    resetBtn.className = 'pegawai-btn reset';
    resetBtn.innerText = "Semua Pegawai";
    resetBtn.onclick = () => filterByPegawai(null);
    container.appendChild(resetBtn);
}



function loadEventsToCalendar(data) {
    const events = data.map(app => ({
        title: app.patient_name,
        start: app.date,
        color: pegawaiColors[app.pegawai_kes],
        extendedProps: { pegawai: app.pegawai_kes }
    }));

    window.calendar.removeAllEvents();
    window.calendar.addEventSource(events);
}

function filterByPegawai(pegawai) {
    selectedPegawai = pegawai;
    const filtered = pegawai ? allAppointments.filter(a => a.pegawai_kes === pegawai) : allAppointments;
    loadEventsToCalendar(filtered);
    updateReminderList(filtered);
}

function updateReminderList(data) {
    const reminderList = document.getElementById('reminder-list');
    reminderList.innerHTML = '';

    if (!data || data.length === 0) {
        reminderList.innerHTML = '<div class="no-appointments">Tiada temujanji akan datang.</div>';
        return;
    }

    data.forEach(app => {
        const dateObj = new Date(app.date);
        const formatted = dateObj.toLocaleDateString('ms-MY', {
            weekday: 'short', day: 'numeric', month: 'short', year: 'numeric'
        });

        const item = document.createElement('div');
        item.className = 'appointment-item';
        item.innerHTML = `
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="width:12px;height:12px;display:inline-block;background:${pegawaiColors[app.pegawai_kes]}"></span>
                <strong>${app.patient_name}</strong>
            </div>
            <div style="font-size:0.9em;color:#666;">${formatted}</div>
        `;
        reminderList.appendChild(item);
    });
}