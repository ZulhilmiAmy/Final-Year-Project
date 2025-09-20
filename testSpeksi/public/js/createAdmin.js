// // GLOBAL VARIABLES
// let data = {};
// let calendar;

// // INITIALIZE PAGE ON LOAD
// document.addEventListener('DOMContentLoaded', function () {
//     // Initialize calendar if element exists
//     const calendarEl = document.getElementById('calendarTab6');
//     if (calendarEl) {
//         calendar = new FullCalendar.Calendar(calendarEl, {
//             initialView: '',
//             height: 'auto',
//             locale: 'ms',
//             headerToolbar: {
//                 left: 'prev,next,today',
//                 center: 'title',
//                 right: ''
//             },
//             buttonText: {
//                 today: 'Hari Ini',
//             },
//             events: @json($events),
//             eventClick: function (info) {
//                 alert(
//                     "Pemohon: " + info.event.title +
//                     "\nPegawai Kes: " + info.event.extendedProps.pegawai +
//                     "\nNo. KP: " + info.event.extendedProps.no_kp +
//                     "\nMasa: " + info.event.start.toLocaleString('ms-MY')
//                 );
//             }
//         });
//         calendar.render();
//     }

//     // Add event listeners for Tab 2 checkboxes
//     document.querySelectorAll('.bantuanpraktik, .terapisokongan').forEach(checkbox => {
//         checkbox.addEventListener('change', function () {
//             validateTab2();
//         });
//     });

//     // Auto-generate No. Fail JKSP on page load
//     let autoId = "JKSP-" + Math.floor(Math.random() * 100000);
//     document.getElementById("nofile").value = autoId;

//     // If IC field has value from request (e.g., from home page)
//     const icInput = document.getElementById("nokp");
//     if (icInput.value.trim() !== "") {
//         autoTarikhUmur(); // Auto-calculate age, birth date & gender
//     }

//     // Add input listeners for error removal
//     document.querySelectorAll("input, textarea, select").forEach(el => {
//         el.addEventListener("input", function () {
//             if (this.tagName !== "SELECT") {
//                 this.classList.remove("error");
//                 let errorMsg = this.parentNode.querySelector(".error-msg");
//                 if (errorMsg) errorMsg.remove();
//             }
//         });

//         el.addEventListener("change", function () {
//             if (this.tagName === "SELECT" || this.type === "date" || this.type === "time") {
//                 if (this.value && this.value !== "") {
//                     this.classList.remove("error");
//                     let errorMsg = this.parentNode.querySelector(".error-msg");
//                     if (errorMsg) errorMsg.remove();
//                 }
//             }
//         });
//     });
//     document.querySelectorAll('.tab').forEach(tab => {
//         tab.addEventListener('click', function () {
//             let tabNum = parseInt(this.dataset.tab);
//             if (!this.classList.contains('disabled')) {
//                 switchTab(tabNum);
//             }
//         });
//     });

//     // Uppercase conversion for all inputs except name, date, and time
//     document.querySelectorAll("input:not(#nama):not([type='date']):not([type='time']), textarea").forEach(el => {
//         el.addEventListener("input", function () {
//             this.value = this.value.toUpperCase();
//         });
//     });
// });

// // FUNCTION TO CALCULATE AGE, BIRTH DATE & GENDER FROM IC NUMBER
// function autoTarikhUmur() {
//     const icInput = document.getElementById("nokp");
//     const dobInput = document.getElementById("tarikhlahir");
//     const ageInput = document.getElementById("umur");
//     const genderInput = document.getElementById("jantina");

//     // Accept numbers with/without dashes, take only digits
//     const nokp = (icInput.value || "").replace(/\D/g, "");

//     // If less than 6 digits (YYMMDD) - clear fields
//     if (nokp.length < 6) {
//         return;
//     }

//     const yy = parseInt(nokp.slice(0, 2), 10);
//     const mm = parseInt(nokp.slice(2, 4), 10);
//     const dd = parseInt(nokp.slice(4, 6), 10);

//     // Basic validation for month & day
//     if (mm < 1 || mm > 12 || dd < 1 || dd > 31) {
//         return;
//     }

//     const today = new Date();
//     const century = (yy <= (today.getFullYear() % 100)) ? 2000 : 1900;
//     const year = century + yy;

//     // Build date and validate (avoid 31/02 etc.)
//     const candidate = new Date(year, mm - 1, dd);
//     if (
//         candidate.getFullYear() !== year ||
//         candidate.getMonth() !== (mm - 1) ||
//         candidate.getDate() !== dd
//     ) {
//         return;
//     }

//     // Format for <input type="date"> (YYYY-MM-DD)
//     const pad2 = n => String(n).padStart(2, "0");
//     dobInput.value = `${year}-${pad2(mm)}-${pad2(dd)}`;

//     // Calculate age
//     let age = today.getFullYear() - year;
//     const belumSambutHariLahir =
//         (today.getMonth() < (mm - 1)) ||
//         (today.getMonth() === (mm - 1) && today.getDate() < dd);
//     if (belumSambutHariLahir) age -= 1;

//     ageInput.value = age;

//     // Determine gender if 12 digits
//     if (nokp.length >= 12) {
//         const lastDigit = parseInt(nokp.charAt(11), 10);
//         genderInput.value = (lastDigit % 2 === 1) ? "Lelaki" : "Perempuan";
//     }
// }

// // FUNCTION TO TOGGLE ADDITIONAL TEXT FIELD FOR "OTHER" OPTIONS
// function toggleAgamaLain() {
//     const select = document.getElementById("agama");
//     const input = document.getElementById("agamaLain");
//     input.style.display = (select.value === "Lain") ? "block" : "none";
// }

// function toggleBangsaLain() {
//     const select = document.getElementById("bangsa");
//     const input = document.getElementById("bangsaLain");
//     input.style.display = (select.value === "Lain") ? "block" : "none";
// }

// function toggleLainTextbox() {
//     const lainCheckbox = document.getElementById('lainCheck');
//     const lainTextbox = document.getElementById('lain');
//     lainTextbox.style.display = lainCheckbox.checked ? 'block' : 'none';
// }

// // FUNCTION TO SWITCH BETWEEN TABS
// function switchTab(num) {
//     document.querySelectorAll('.compartment').forEach(c => c.classList.remove('active'));
//     document.querySelector(`#compartment${num}`).classList.add('active');
//     document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
//     document.querySelector(`.tab[data-tab="${num}"]`).classList.add('active');
//     document.querySelector(`.tab[data-tab="${num}"]`).classList.remove('disabled');

//     // Redraw calendar when opening Tab 6
//     if (num === 6 && calendar) {
//         setTimeout(() => {
//             calendar.updateSize();
//         }, 200);
//     }
// }

// // FUNCTION TO NAVIGATE TO NEXT TAB
// function next(num) {
//     // Special validation for each tab
//     let isValid = true;

//     if (num === 1) {
//         isValid = validateTab1();
//     } else if (num === 2) {
//         isValid = validateTab2();
//     } else if (num === 3) {
//         isValid = validateTab3();
//     } else if (num === 4) {
//         isValid = validateTab4();
//     } else if (num === 5) {
//         isValid = validateTab5();
//     } else if (num === 6) {
//         isValid = validateTab6();
//     }

//     if (!isValid) return false;

//     simpan(num, true);
//     document.querySelector(`.tab[data-tab="${num + 1}"]`).classList.remove('disabled');
//     switchTab(num + 1);
// }

// // FUNCTION TO NAVIGATE TO PREVIOUS TAB
// function back(num) {
//     simpan(num, false); // Save but don't show notification
//     switchTab(num - 1);
// }

// // FUNCTION TO VALIDATE TAB 1 (MAKLUMAT PERIBADI)
// function validateTab1() {
//     const requiredFields = [
//         'tarikhrujukan', 'nama', 'nokp', 'agama', 'bangsa',
//         'alamat', 'negeri', 'bandar', 'poskod'
//     ];

//     let isValid = true;

//     // Validate all required fields
//     requiredFields.forEach(fieldId => {
//         const field = document.getElementById(fieldId);
//         if (field && !field.value.trim()) {
//             field.classList.add('error');
//             isValid = false;
//         } else if (field) {
//             field.classList.remove('error');
//         }
//     });

//     // Validate IC number format
//     const icField = document.getElementById('nokp');
//     if (icField && icField.value.trim()) {
//         const icValue = icField.value.replace(/\D/g, '');
//         if (icValue.length < 6) {
//             icField.classList.add('error');
//             isValid = false;
//         }
//     }

//     // Validate birth date
//     const dobField = document.getElementById('tarikhlahir');
//     if (dobField && !dobField.value.trim()) {
//         dobField.classList.add('error');
//         isValid = false;
//     }

//     // Validate age
//     const ageField = document.getElementById('umur');
//     if (ageField && (!ageField.value.trim() || isNaN(ageField.value) || ageField.value < 0)) {
//         ageField.classList.add('error');
//         isValid = false;
//     }

//     // Validate gender
//     const genderField = document.getElementById('jantina');
//     if (genderField && !genderField.value) {
//         genderField.classList.add('error');
//         isValid = false;
//     }

//     // Show/hide error message
//     const errorDiv = document.getElementById('tab1-error');
//     if (errorDiv) {
//         if (!isValid) {
//             errorDiv.style.display = 'block';
//         } else {
//             errorDiv.style.display = 'none';
//         }
//     }

//     return isValid;
// }

// // FUNCTION TO VALIDATE TAB 2 (TUJUAN RUJUKAN)
// function validateTab2() {
//     const bantuanCheckboxes = document.querySelectorAll('.bantuanpraktik:checked');
//     const terapiCheckboxes = document.querySelectorAll('.terapisokongan:checked');
//     const errorMsg = document.getElementById('tab2-error');

//     // Check if at least one checkbox is selected
//     const isValid = bantuanCheckboxes.length > 0 || terapiCheckboxes.length > 0;

//     if (!isValid) {
//         // Mark checkbox groups with red border
//         document.getElementById('bantuan-praktik-group').classList.add('error-border');
//         document.getElementById('terapi-sokongan-group').classList.add('error-border');
//         if (errorMsg) errorMsg.style.display = 'block';
//         return false;
//     }

//     // Remove error indicators if valid
//     document.getElementById('bantuan-praktik-group').classList.remove('error-border');
//     document.getElementById('terapi-sokongan-group').classList.remove('error-border');
//     if (errorMsg) errorMsg.style.display = 'none';

//     return true;
// }

// // FUNCTION TO VALIDATE TAB 3 (KATEGORI KES)
// function validateTab3() {
//     const allCheckboxes = document.querySelectorAll('#compartment3 input[type="checkbox"]');
//     const lainCheck = document.getElementById("lainCheck");
//     const lainInput = document.getElementById("lain");
//     const errorMsg = document.getElementById('tab3-error');

//     let anyChecked = [...allCheckboxes].some(cb => cb.checked);
//     let isValid = true;

//     if (!anyChecked) {
//         isValid = false;
//         document.querySelectorAll('#compartment3 .checkbox-group')
//             .forEach(group => group.classList.add('error-border'));
//     } else {
//         document.querySelectorAll('#compartment3 .checkbox-group')
//             .forEach(group => group.classList.remove('error-border'));
//     }

//     // If "other" is checked but text is empty → error
//     if (lainCheck && lainCheck.checked) {
//         if (!lainInput.value.trim()) {
//             isValid = false;
//             lainInput.classList.add("error");
//             let errorMsg = lainInput.parentNode.querySelector(".error-msg");
//             if (!errorMsg) {
//                 errorMsg = document.createElement("div");
//                 errorMsg.classList.add("error-msg");
//                 errorMsg.style.color = "red";
//                 errorMsg.style.fontSize = "12px";
//                 errorMsg.style.marginTop = "3px";
//                 lainInput.parentNode.appendChild(errorMsg);
//             }
//             errorMsg.innerText = "Sila nyatakan kategori lain-lain.";
//         } else {
//             lainInput.classList.remove("error");
//             let errorMsg = lainInput.parentNode.querySelector(".error-msg");
//             if (errorMsg) errorMsg.remove();
//         }
//     }

//     // Show/hide error message
//     if (errorMsg) {
//         if (!isValid) {
//             errorMsg.style.display = 'block';
//         } else {
//             errorMsg.style.display = 'none';
//         }
//     }

//     return isValid;
// }

// // FUNCTION TO VALIDATE TAB 4 (MAKLUMAT PERUJUK)
// function validateTab4() {
//     const namaPerujuk = document.getElementById('namaperujuk').value.trim();
//     const disiplin = document.getElementById('disiplin').value;
//     const wadRujuk = document.getElementById('wadrujuk').value;
//     const diagnosisRujuk = document.getElementById('diagnosisrujuk').value.trim();
//     const errorMsg = document.getElementById('tab4-error');

//     let isValid = true;

//     // Check each required field
//     if (!namaPerujuk) {
//         document.getElementById('namaperujuk').classList.add('error');
//         isValid = false;
//     } else {
//         document.getElementById('namaperujuk').classList.remove('error');
//     }

//     if (!disiplin) {
//         document.getElementById('disiplin').classList.add('error');
//         isValid = false;
//     } else {
//         document.getElementById('disiplin').classList.remove('error');
//     }

//     if (!wadRujuk) {
//         document.getElementById('wadrujuk').classList.add('error');
//         isValid = false;
//     } else {
//         document.getElementById('wadrujuk').classList.remove('error');
//     }

//     if (!diagnosisRujuk) {
//         document.getElementById('diagnosisrujuk').classList.add('error');
//         isValid = false;
//     } else {
//         document.getElementById('diagnosisrujuk').classList.remove('error');
//     }

//     // Show/hide error message
//     if (errorMsg) {
//         if (!isValid) {
//             errorMsg.style.display = 'block';
//         } else {
//             errorMsg.style.display = 'none';
//         }
//     }

//     return isValid;
// }

// // FUNCTION TO VALIDATE TAB 5 (RUJUKAN AGENSI)
// function validateTab5() {
//     // Tab 5 doesn't have required fields, so always return true
//     return true;
// }

// // FUNCTION TO VALIDATE TAB 6 (TARIKH TEMU JANJI)
// function validateTab6() {
//     const pegawai = document.getElementById('pegawaikes').value.trim();
//     const tarikh = document.getElementById('tarikhtemujanji').value;
//     const masa = document.getElementById('masatemujanji').value;
//     const errorMsg = document.getElementById('tab6-error');
//     let isValid = true;

//     // Check each required field
//     if (!pegawai) {
//         document.getElementById('pegawaikes').classList.add('error');
//         isValid = false;
//     } else {
//         document.getElementById('pegawaikes').classList.remove('error');
//     }

//     if (!tarikh) {
//         document.getElementById('tarikhtemujanji').classList.add('error');
//         isValid = false;
//     } else {
//         document.getElementById('tarikhtemujanji').classList.remove('error');
//     }

//     if (!masa) {
//         document.getElementById('masatemujanji').classList.add('error');
//         isValid = false;
//     } else {
//         document.getElementById('masatemujanji').classList.remove('error');
//     }

//     // Show/hide error message
//     if (errorMsg) {
//         if (!isValid) {
//             errorMsg.style.display = 'block';
//         } else {
//             errorMsg.style.display = 'none';
//         }
//     }

//     return isValid;
// }

// // FUNCTION TO VALIDATE CURRENT TAB
// function validateTab(num) {
//     if (num === 1) return validateTab1();
//     if (num === 2) return validateTab2();
//     if (num === 3) return validateTab3();
//     if (num === 4) return validateTab4();
//     if (num === 5) return validateTab5();
//     if (num === 6) return validateTab6();
//     return true;
// }

// // FUNCTION TO SAVE CURRENT TAB DATA
// function simpan(num, showNotif = true) {
//     if (!validateTab(num)) return false;

//     // If all valid → save data
//     let inputs = document.querySelectorAll(`#compartment${num} input, #compartment${num} textarea, #compartment${num} select`);
//     inputs.forEach(i => {
//         if (i.type === "checkbox") {
//             data[i.id || i.className] = i.checked ? "Ya" : "Tidak";
//         } else {
//             data[i.id] = i.value;
//         }
//     });

//     if (showNotif) {
//         const notif = document.getElementById("notif");
//         notif.innerText = `Bahagian ${num} berjaya disimpan! ✅`;
//         notif.style.display = "block";
//         setTimeout(() => {
//             notif.style.display = "none";
//         }, 2000);
//     }

//     return true;
// }

// // FUNCTION TO SAVE APPOINTMENT TO LOCALSTORAGE
// function saveAppointment(appointment) {
//     const appointments = loadAppointments();
//     appointments.push(appointment);
//     localStorage.setItem('patientAppointments', JSON.stringify(appointments));
// }

// // FUNCTION TO LOAD APPOINTMENTS FROM LOCALSTORAGE
// function loadAppointments() {
//     const appointmentsJSON = localStorage.getItem('patientAppointments');
//     if (appointmentsJSON) {
//         return JSON.parse(appointmentsJSON);
//     }
//     return [];
// }

// // FUNCTION TO SAVE APPOINTMENT (TAB 6)
// function simpanTemujanji() {
//     if (!validateTab6()) {
//         return false;
//     }

//     const pegawai = document.getElementById('pegawaikes').value;
//     const tarikh = document.getElementById('tarikhtemujanji').value;
//     const masa = document.getElementById('masatemujanji').value;
//     const catatan = document.getElementById('catatantemujanji').value;

//     const startDateTime = tarikh + "T" + masa;
//     const appointment = {
//         title: `${pegawai || "Temu Janji"} - ${catatan || "Tiada catatan"}`,
//         start: startDateTime,
//         allDay: false,
//         extendedProps: {
//             pegawai: pegawai,
//             catatan: catatan
//         }
//     };

//     // Add to calendar if available
//     if (calendar) {
//         calendar.addEvent(appointment);
//     }

//     saveAppointment(appointment);

//     // Clear form fields
//     document.getElementById('pegawaikes').value = '';
//     document.getElementById('tarikhtemujanji').value = '';
//     document.getElementById('masatemujanji').value = '';
//     document.getElementById('catatantemujanji').value = '';

//     // Show success notification
//     const notif = document.getElementById("notif");
//     notif.innerText = "Temu janji berjaya disimpan! ✅";
//     notif.style.display = "block";
//     setTimeout(() => { notif.style.display = "none"; }, 2000);

//     return true;
// }

// // FUNCTION TO VALIDATE FINAL FORM SUBMISSION
// function validateFinal() {
//     // Ensure all inputs in tab 6 are valid
//     if (!validateTab6()) {
//         switchTab(6); // Stay on tab 6
//         return false; // Don't submit
//     }

//     return true; // Submit to server
// }

// // FUNCTION TO CONFIRM NAVIGATION TO HOME PAGE
// function confirmHome(e) {
//     e.preventDefault();
//     if (confirm("Anda pasti mahu kembali ke Halaman Utama? Maklumat yang belum disimpan mungkin akan hilang.")) {
//         window.location.href = e.target.href;
//     }
//     return false;
// }

// // FUNCTION TO CONFIRM NAVIGATION TO LOGIN PAGE
// function confirmGoLogin() {
//     if (confirm("Adakah anda pasti mahu kembali ke halaman Login?")) {
//         window.location.href = "{{ route('login.custom') }}";
//     }
// }

// // ADD CLICK LISTENERS TO TABS
// // document.querySelectorAll('.tab').forEach(tab => {
// //     tab.addEventListener('click', function () {
// //         let tabNum = parseInt(this.dataset.tab);
// //         if (!this.classList.contains('disabled')) {
// //             switchTab(tabNum);
// //         }
// //     });
// // });

// // FUNCTION TO CALCULATE AGE FROM BIRTH DATE
// function kiraUmurDariTarikh() {
//     const dobInput = document.getElementById("tarikhlahir");
//     const ageInput = document.getElementById("umur");

//     if (!dobInput.value) return;

//     const birthDate = new Date(dobInput.value);
//     const today = new Date();

//     let age = today.getFullYear() - birthDate.getFullYear();
//     const monthDiff = today.getMonth() - birthDate.getMonth();

//     if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
//         age--;
//     }

//     ageInput.value = age;
// }

// // FUNCTION TO CALCULATE BIRTH DATE FROM AGE
// function kiraTarikhLahirDariUmur() {
//     const ageInput = document.getElementById("umur");
//     const dobInput = document.getElementById("tarikhlahir");

//     if (!ageInput.value) return;

//     const today = new Date();
//     const birthYear = today.getFullYear() - parseInt(ageInput.value);

//     // Set to January 1st of the calculated year
//     const calculatedDate = new Date(birthYear, 0, 1);

//     // Format for <input type="date"> (YYYY-MM-DD)
//     const pad2 = n => String(n).padStart(2, "0");
//     dobInput.value = `${calculatedDate.getFullYear()}-${pad2(calculatedDate.getMonth() + 1)}-${pad2(calculatedDate.getDate())}`;
// }
