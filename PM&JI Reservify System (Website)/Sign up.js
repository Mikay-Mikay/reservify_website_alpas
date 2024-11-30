document.addEventListener("DOMContentLoaded", function() {
  // Get the checkbox and the register button elements
  const termsCheckbox = document.getElementById('terms');
  const registerButton = document.getElementById('register-btn');

  // Function to enable/disable the button based on checkbox status
  termsCheckbox.addEventListener('change', function() {
      if (termsCheckbox.checked) {
          registerButton.disabled = false; // Enable button
      } else {
          registerButton.disabled = true; // Disable button
      }
  });

  // List of cities in the Philippines
  const cities = [
      "Alaminos City", "Batac City", "Candon City", "Dagupan City", "Laoag City", 
      "San Carlos City (Pangasinan)", "San Fernando City (La Union)", "Vigan City",
      "Cauayan City", "Ilagan City", "Santiago City", "Tuguegarao City",
      "Angeles City", "Balanga City", "Cabanatuan City", "Gapan City", "Malolos City", 
      "Meycauayan City", "Mabalacat City", "Olongapo City", "Palayan City", "San Fernando City (Pampanga)", 
      "San Jose City (Nueva Ecija)", "San Jose del Monte City", "Tarlac City", "Antipolo City", 
      "Bacoor City", "Batangas City", "Biñan City", "Cabuyao City", "Calamba City", "Cavite City", 
      "Dasmariñas City", "General Trias City", "Imus City", "Lipa City", "Lucena City", 
      "San Pablo City", "Santa Rosa City", "Tanauan City", "Tayabas City", "Calapan City", 
      "Puerto Princesa City", "Iriga City", "Legazpi City", "Masbate City", "Naga City (Camarines Sur)", 
      "Sorsogon City", "Tabaco City", "Bacolod City", "Bago City", "Cadiz City", "Escalante City", 
      "Himamaylan City", "Iloilo City", "Kabankalan City", "La Carlota City", "Passi City", 
      "Roxas City", "Sagay City", "San Carlos City (Negros Occidental)", "Silay City", "Talisay City", "San Jose Del Monte City",
      "Victorias City", "Bais City", "Bayawan City", "Carcar City", "Cebu City", "Danao City", 
      "Dumaguete City", "Lapu-Lapu City", "Mandaue City", "Naga City (Cebu)", "Talisay City (Cebu)", 
      "Toledo City", "Baybay City", "Borongan City", "Calbayog City", "Catbalogan City", "Maasin City", 
      "Ormoc City", "Tacloban City", "Dapitan City", "Dipolog City", "Isabela City (Basilan)", 
      "Pagadian City", "Zamboanga City", "Cagayan de Oro City", "El Salvador City", "Gingoog City", 
      "Malaybalay City", "Oroquieta City", "Tangub City", "Valencia City", "Davao City", "Digos City", 
      "Mati City", "Panabo City", "Samal City", "Tagum City", "Cotabato City", "General Santos City", 
      "Kidapawan City", "Koronadal City", "Tacurong City", "Bayugan City", "Bislig City", "Butuan City", 
      "Cabadbaran City", "Surigao City", "Tandag City", "Lamitan City", "Marawi City"
  ];

  // List of regions in the Philippines
  const regions = [
      "National Capital Region (NCR)", "Cordillera Administrative Region (CAR)", 
      "Ilocos Region (Region I)", "Cagayan Valley (Region II)","Central Luzon(Region III)", "Calabarzon (Region IV-A)", 
      "Mimaropa (Region IV-B)", "Bicol Region (Region V)", "Western Visayas (Region VI)", 
      "Central Visayas (Region VII)", "Eastern Visayas (Region VIII)", "Zamboanga Peninsula (Region IX)", 
      "Northern Mindanao (Region X)", "Davao Region (Region XI)", "Soccsksargen (Region XII)", 
      "Caraga (Region XIII)", "Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)"
  ];

  // Select the city and region dropdown elements
  const citySelect = document.getElementById("citySelect");
  const regionSelect = document.getElementById("regionSelect");

  // Populate the city dropdown
  cities.forEach(city => {
      const option = document.createElement("option");
      option.value = city;
      option.textContent = city;
      citySelect.appendChild(option);
  });

  // Populate the region dropdown
  regions.forEach(region => {
      const option = document.createElement("option");
      option.value = region;
      option.textContent = region;
      regionSelect.appendChild(option);
  });

  // Password toggle functionality (existing code)
  const togglePassword = document.getElementById('toggle-password');
  const passwordInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eye-icon');

  togglePassword.addEventListener('click', function () {
      if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          eyeIcon.classList.remove('fa-eye');
          eyeIcon.classList.add('fa-eye-slash'); // Update icon to eye-slash
      } else {
          passwordInput.type = 'password';
          eyeIcon.classList.remove('fa-eye-slash');
          eyeIcon.classList.add('fa-eye'); // Revert to eye icon
      }
  });
});
document.addEventListener("DOMContentLoaded", function() {
  // List of countries in alphabetical order
  const countries = [
    { code: 'afghanistan', name: 'Afghanistan' },
    { code: 'albania', name: 'Albania' },
    { code: 'algeria', name: 'Algeria' },
    { code: 'andorra', name: 'Andorra' },
    { code: 'angola', name: 'Angola' },
    { code: 'anguilla', name: 'Anguilla' },
    { code: 'antigua-and-barbuda', name: 'Antigua and Barbuda' },
    { code: 'argentina', name: 'Argentina' },
    { code: 'armenia', name: 'Armenia' },
    { code: 'australia', name: 'Australia' },
    { code: 'austria', name: 'Austria' },
    { code: 'azerbaijan', name: 'Azerbaijan' },
    { code: 'bahamas', name: 'Bahamas' },
    { code: 'bahrain', name: 'Bahrain' },
    { code: 'bangladesh', name: 'Bangladesh' },
    { code: 'barbados', name: 'Barbados' },
    { code: 'belarus', name: 'Belarus' },
    { code: 'belgium', name: 'Belgium' },
    { code: 'belize', name: 'Belize' },
    { code: 'benin', name: 'Benin' },
    { code: 'bhutan', name: 'Bhutan' },
    { code: 'bolivia', name: 'Bolivia' },
    { code: 'bosnia-and-herzegovina', name: 'Bosnia and Herzegovina' },
    { code: 'botswana', name: 'Botswana' },
    { code: 'brazil', name: 'Brazil' },
    { code: 'brunei', name: 'Brunei' },
    { code: 'bulgaria', name: 'Bulgaria' },
    { code: 'burkina-faso', name: 'Burkina Faso' },
    { code: 'burundi', name: 'Burundi' },
    { code: 'cabo-verde', name: 'Cabo Verde' },
    { code: 'cambodia', name: 'Cambodia' },
    { code: 'cameroon', name: 'Cameroon' },
    { code: 'canada', name: 'Canada' },
    { code: 'central-african-republic', name: 'Central African Republic' },
    { code: 'chad', name: 'Chad' },
    { code: 'chile', name: 'Chile' },
    { code: 'china', name: 'China' },
    { code: 'colombia', name: 'Colombia' },
    { code: 'comoros', name: 'Comoros' },
    { code: 'congo', name: 'Congo' },
    { code: 'costa-rica', name: 'Costa Rica' },
    { code: 'croatia', name: 'Croatia' },
    { code: 'cuba', name: 'Cuba' },
    { code: 'cyprus', name: 'Cyprus' },
    { code: 'czech-republic', name: 'Czech Republic' },
    { code: 'democratic-republic-of-the-congo', name: 'Democratic Republic of the Congo' },
    { code: 'denmark', name: 'Denmark' },
    { code: 'djibouti', name: 'Djibouti' },
    { code: 'dominica', name: 'Dominica' },
    { code: 'dominican-republic', name: 'Dominican Republic' },
    { code: 'ecuador', name: 'Ecuador' },
    { code: 'egypt', name: 'Egypt' },
    { code: 'el-salvador', name: 'El Salvador' },
    { code: 'equatorial-guinea', name: 'Equatorial Guinea' },
    { code: 'eritrea', name: 'Eritrea' },
    { code: 'estonia', name: 'Estonia' },
    { code: 'eswatini', name: 'Eswatini' },
    { code: 'ethiopia', name: 'Ethiopia' },
    { code: 'fiji', name: 'Fiji' },
    { code: 'finland', name: 'Finland' },
    { code: 'france', name: 'France' },
    { code: 'gabon', name: 'Gabon' },
    { code: 'gambia', name: 'Gambia' },
    { code: 'georgia', name: 'Georgia' },
    { code: 'germany', name: 'Germany' },
    { code: 'ghana', name: 'Ghana' },
    { code: 'greece', name: 'Greece' },
    { code: 'grenada', name: 'Grenada' },
    { code: 'guatemala', name: 'Guatemala' },
    { code: 'guinea', name: 'Guinea' },
    { code: 'guinea-bissau', name: 'Guinea-Bissau' },
    { code: 'guyana', name: 'Guyana' },
    { code: 'haiti', name: 'Haiti' },
    { code: 'honduras', name: 'Honduras' },
    { code: 'hungary', name: 'Hungary' },
    { code: 'iceland', name: 'Iceland' },
    { code: 'india', name: 'India' },
    { code: 'indonesia', name: 'Indonesia' },
    { code: 'iran', name: 'Iran' },
    { code: 'iraq', name: 'Iraq' },
    { code: 'ireland', name: 'Ireland' },
    { code: 'israel', name: 'Israel' },
    { code: 'italy', name: 'Italy' },
    { code: 'jamaica', name: 'Jamaica' },
    { code: 'japan', name: 'Japan' },
    { code: 'jordan', name: 'Jordan' },
    { code: 'kazakhstan', name: 'Kazakhstan' },
    { code: 'kenya', name: 'Kenya' },
    { code: 'kiribati', name: 'Kiribati' },
    { code: 'korea-north', name: 'North Korea' },
    { code: 'korea-south', name: 'South Korea' },
    { code: 'kuwait', name: 'Kuwait' },
    { code: 'kyrgyzstan', name: 'Kyrgyzstan' },
    { code: 'laos', name: 'Laos' },
    { code: 'latvia', name: 'Latvia' },
    { code: 'lebanon', name: 'Lebanon' },
    { code: 'lesotho', name: 'Lesotho' },
    { code: 'liberia', name: 'Liberia' },
    { code: 'libya', name: 'Libya' },
    { code: 'liechtenstein', name: 'Liechtenstein' },
    { code: 'lithuania', name: 'Lithuania' },
    { code: 'luxembourg', name: 'Luxembourg' },
    { code: 'madagascar', name: 'Madagascar' },
    { code: 'malawi', name: 'Malawi' },
    { code: 'malaysia', name: 'Malaysia' },
    { code: 'maldives', name: 'Maldives' },
    { code: 'mali', name: 'Mali' },
    { code: 'malta', name: 'Malta' },
    { code: 'marshall-islands', name: 'Marshall Islands' },
    { code: 'mauritania', name: 'Mauritania' },
    { code: 'mauritius', name: 'Mauritius' },
    { code: 'mexico', name: 'Mexico' },
    { code: 'micronesia', name: 'Micronesia' },
    { code: 'moldova', name: 'Moldova' },
    { code: 'monaco', name: 'Monaco' },
    { code: 'mongolia', name: 'Mongolia' },
    { code: 'morocco', name: 'Morocco' },
    { code: 'mozambique', name: 'Mozambique' },
    { code: 'myanmar', name: 'Myanmar' },
    { code: 'namibia', name: 'Namibia' },
    { code: 'nauru', name: 'Nauru' },
    { code: 'nepal', name: 'Nepal' },
    { code: 'netherlands', name: 'Netherlands' },
    { code: 'new-zealand', name: 'New Zealand' },
    { code: 'nicaragua', name: 'Nicaragua' },
    { code: 'niger', name: 'Niger' },
    { code: 'nigeria', name: 'Nigeria' },
    { code: 'north-macedonia', name: 'North Macedonia' },
    { code: 'norway', name: 'Norway' },
    { code: 'oman', name: 'Oman' },
    { code: 'pakistan', name: 'Pakistan' },
    { code: 'palau', name: 'Palau' },
    { code: 'panama', name: 'Panama' },
    { code: 'papua-new-guinea', name: 'Papua New Guinea' },
    { code: 'paraguay', name: 'Paraguay' },
    { code: 'peru', name: 'Peru' },
    { code: 'philippines', name: 'Philippines' },
    { code: 'poland', name: 'Poland' },
    { code: 'portugal', name: 'Portugal' },
    { code: 'qatar', name: 'Qatar' },
    { code: 'romania', name: 'Romania' },
    { code: 'russia', name: 'Russia' },
    { code: 'rwanda', name: 'Rwanda' },
    { code: 'saint-kitts-and-nevis', name: 'Saint Kitts and Nevis' },
    { code: 'saint-lucia', name: 'Saint Lucia' },
    { code: 'saint-vincent-and-the-grenadines', name: 'Saint Vincent and the Grenadines' },
    { code: 'samoa', name: 'Samoa' },
    { code: 'san-marino', name: 'San Marino' },
    { code: 'sao-tome-and-principe', name: 'Sao Tome and Principe' },
    { code: 'saudi-arabia', name: 'Saudi Arabia' },
    { code: 'senegal', name: 'Senegal' },
    { code: 'serbia', name: 'Serbia' },
    { code: 'seychelles', name: 'Seychelles' },
    { code: 'sierra-leone', name: 'Sierra Leone' },
    { code: 'singapore', name: 'Singapore' },
    { code: 'slovakia', name: 'Slovakia' },
    { code: 'slovenia', name: 'Slovenia' },
    { code: 'solomon-islands', name: 'Solomon Islands' },
    { code: 'somalia', name: 'Somalia' },
    { code: 'south-africa', name: 'South Africa' },
    { code: 'south-sudan', name: 'South Sudan' },
    { code: 'spain', name: 'Spain' },
    { code: 'sri-lanka', name: 'Sri Lanka' },
    { code: 'sudan', name: 'Sudan' },
    { code: 'suriname', name: 'Suriname' },
    { code: 'sweden', name: 'Sweden' },
    { code: 'switzerland', name: 'Switzerland' },
    { code: 'syria', name: 'Syria' },
    { code: 'taiwan', name: 'Taiwan' },
    { code: 'tajikistan', name: 'Tajikistan' },
    { code: 'tanzania', name: 'Tanzania' },
    { code: 'thailand', name: 'Thailand' },
    { code: 'togo', name: 'Togo' },
    { code: 'tonga', name: 'Tonga' },
    { code: 'trinidad-and-tobago', name: 'Trinidad and Tobago' },
    { code: 'tunisia', name: 'Tunisia' },
    { code: 'turkmenistan', name: 'Turkmenistan' },
    { code: 'turkey', name: 'Turkey' },
    { code: 'tuvalu', name: 'Tuvalu' },
    { code: 'uganda', name: 'Uganda' },
    { code: 'ukraine', name: 'Ukraine' },
    { code: 'united-arab-emirates', name: 'United Arab Emirates' },
    { code: 'united-kingdom', name: 'United Kingdom' },
    { code: 'united-states', name: 'United States' },
    { code: 'uruguay', name: 'Uruguay' },
    { code: 'uzbekistan', name: 'Uzbekistan' },
    { code: 'vanuatu', name: 'Vanuatu' },
    { code: 'venezuela', name: 'Venezuela' },
    { code: 'vietnam', name: 'Vietnam' },
    { code: 'yemen', name: 'Yemen' },
    { code: 'zambia', name: 'Zambia' },
    { code: 'zimbabwe', name: 'Zimbabwe' }
  ];

  // Select the country dropdown element
  const countrySelect = document.getElementById('countrySelect');

  // Function to fetch and populate the country dropdown
  function fetchCountries() {
    // Sort countries alphabetically by their name
    countries.sort((a, b) => a.name.localeCompare(b.name));

    // Populate the dropdown
    countries.forEach(country => {
      const option = document.createElement("option");
      option.value = country.code;
      option.textContent = country.name;
      countrySelect.appendChild(option);
    });
  }

  // Call the fetchCountries function to populate the dropdown
  fetchCountries();
});

document.addEventListener("DOMContentLoaded", function() {
  const barangays = [
    "Barangay 1 (Pob.)", "Barangay 10 (Pob.)", "Barangay 100", "Barangay 101", "Barangay 102",
    "Barangay 103", "Barangay 104", "Barangay 105", "Barangay 106", "Barangay 107", "Barangay 108",
    "Barangay 109", "Barangay 11 (Pob.)", "Barangay 110", "Barangay 111", "Barangay 112", "Barangay 113",
    "Barangay 114", "Barangay 115", "Barangay 116", "Barangay 117", "Barangay 118", "Barangay 119",
    "Barangay 12 (Pob.)", "Barangay 120", "Barangay 121", "Barangay 122", "Barangay 123", "Barangay 124",
    "Barangay 125", "Barangay 126", "Barangay 127", "Barangay 128", "Barangay 129", "Barangay 13 (Pob.)",
    "Barangay 130", "Barangay 131", "Barangay 132", "Barangay 133", "Barangay 134", "Barangay 135",
    "Barangay 136", "Barangay 137", "Barangay 138", "Barangay 139", "Barangay 14 (Pob.)", "Barangay 140",
    "Barangay 141", "Barangay 142", "Barangay 143", "Barangay 144", "Barangay 145", "Barangay 146",
    "Barangay 147", "Barangay 148", "Barangay 149", "Barangay 15 (Pob.)", "Barangay 150", "Barangay 16 (Pob.)",
    "Barangay 17 (Pob.)", "Barangay 18 (Pob.)", "Barangay 19 (Pob.)", "Barangay 2 (Pob.)", "Barangay 20",
    "Barangay 21", "Barangay 22", "Barangay 23", "Barangay 24", "Barangay 25", "Barangay 26", "Barangay 27",
    "Barangay 28", "Barangay 29", "Barangay 3 (Pob.)", "Barangay 30", "Barangay 31", "Barangay 32", "Barangay 33",
    "Barangay 34", "Barangay 35", "Barangay 36", "Barangay 37", "Barangay 38", "Barangay 39", "Barangay 4 (Pob.)",
    "Barangay 40", "Barangay 41", "Barangay 42", "Barangay 43", "Barangay 44", "Barangay 45", "Barangay 46",
    "Barangay 47", "Barangay 48", "Barangay 49", "Barangay 5 (Pob.)", "Barangay 50", "Barangay 51", "Barangay 52",
    "Barangay 53", "Barangay 54", "Barangay 55", "Barangay 56", "Barangay 57", "Barangay 58", "Barangay 59",
    "Barangay 6 (Pob.)", "Barangay 60", "Barangay 61", "Barangay 62", "Barangay 63", "Barangay 64", "Barangay 65",
    "Barangay 66", "Barangay 67", "Barangay 68", "Barangay 69", "Barangay 7 (Pob.)", "Barangay 70", "Barangay 71",
    "Barangay 72", "Barangay 73", "Barangay 74", "Barangay 75", "Barangay 76", "Barangay 77", "Barangay 78",
    "Barangay 79", "Barangay 8 (Pob.)", "Barangay 80", "Barangay 81", "Barangay 82", "Barangay 83", "Barangay 84",
    "Barangay 85", "Barangay 86", "Barangay 87", "Barangay 88", "Barangay 89", "Barangay 9 (Pob.)", "Barangay 90",
    "Barangay 91", "Barangay 92", "Barangay 93", "Barangay 94", "Barangay 95", "Barangay 96", "Barangay 97",
    "Barangay 98", "Barangay 99", "Barangay Alangilan", "Barangay Antipolo", "Barangay Bagumbayan", "Barangay Banaba",
    "Barangay Banuyo", "Barangay Banang", "Barangay Balintawak", "Barangay Bacolor", "Barangay Buli", "Barangay Barangka",
    "Barangay Calanipawan", "Barangay Casimiro", "Barangay Camachile", "Barangay Del Carmen", "Barangay Dela Paz", 
    "Barangay Don Juan", "Barangay Dulag", "Barangay Dulong Bayan", "Barangay Ermita", "Barangay Galut", "Barangay Inocencio",
    "Barangay Kamuning", "Barangay Lawa", "Barangay Lungsod", "Barangay Malasakit", "Barangay Manang", "Barangay Maligayang", 
    "Barangay Matandang Balara", "Barangay Muntinlupa", "Barangay Nangka", "Barangay New Manila", "Barangay San Francisco", 
    "Barangay San Jose", "Barangay San Miguel", "Barangay Silang", "Barangay Silay", "Barangay Tabing Ilog", "Barangay Tarlac", 
    "Barangay Tandang Sora", "Barangay Pagsawitan", "Barangay Salomague", "Barangay Santa Monica", "Barangay Sampiro", 
    "Barangay San Vicente", "Barangay Talisay", "Barangay Pugad", "Barangay Pook", "Barangay San Felipe", "Barangay San Juan", 
    "Barangay Turo", "Barangay San Isidro", "Barangay Tabang", "Barangay Tanauan", "Barangay Talon", "Barangay Wawa","Barangay Muzon",  "Barangay Minuyan Proper",
    "Barangay Sapang Palay", "Barangay Sto. Niño","Barangay San Isidro", "Barangay San Jose del Monte (Poblacion)","Barangay Tungkong Mangga",
    "Barangay Minuyan", "Barangay Dulong Bayan", "Barangay Kaybanban",  "Barangay Bagumbayan", "Barangay Cupang", "Barangay Longos", "Barangay Santa Clara",
    "Barangay Poblacion", "Barangay San Vicente", "Barangay San Jose", "Barangay Sto. Niño",
    "Barangay San Juan", "Barangay San Rafael", "Barangay San Pedro", "Barangay Sampaloc",
    "Barangay San Isidro", "Barangay San Antonio", "Barangay Dolores", "Barangay San Mateo",
    "Barangay San Pedro", "Barangay Barangka", "Barangay Ibayo", "Barangay Silangan",   "Barangay 1 (Pob.)", "Barangay 2 (Pob.)", "Barangay 3 (Pob.)", "Barangay 4 (Pob.)", "Barangay 5 (Pob.)",
    "Barangay 6 (Pob.)", "Barangay 7 (Pob.)", "Barangay 8 (Pob.)", "Barangay 9 (Pob.)", "Barangay 10 (Pob.)",
    "Barangay 11 (Pob.)", "Barangay 12 (Pob.)", "Barangay 13 (Pob.)", "Barangay 14 (Pob.)", "Barangay 15 (Pob.)",
    "Barangay Bagumbong", "Barangay Malaria", "Barangay Sipac-Almacén", "Barangay Deparo", "Barangay Novaliches",
    "Barangay Bagumbayan", "Barangay Kangkong", "Barangay Concepcion", "Barangay Talipapa", "Barangay Malinta",
    "Barangay Caloocan West", "Barangay La Loma", "Barangay Amparo", "Barangay Pangarap Village", "Barangay Tala",

    // Barangays in Quezon City
    "Barangay Bagumbayan", "Barangay Baño", "Barangay Batasan Hills", "Barangay Commonwealth", "Barangay Culiat",
    "Barangay Damayang Lagi", "Barangay Galas", "Barangay Kaduyao", "Barangay Kaingin Road", "Barangay Laging Handa",
    "Barangay Libis", "Barangay Lungsod Silangan", "Barangay Matandang Balara", "Barangay Payatas", "Barangay Pansol",
    "Barangay Project 1", "Barangay Project 2", "Barangay Project 3", "Barangay Project 4", "Barangay Project 6",
    "Barangay San Antonio", "Barangay San Isidro", "Barangay San Jose", "Barangay San Juan", "Barangay San Martin de Porres",
    "Barangay San Mateo", "Barangay Sangandaan", "Barangay Santo Niño", "Barangay Sauyo", "Barangay South Triangle",
    "Barangay Tandang Sora", "Barangay UP Campus", "Barangay University Heights", "Barangay Victoria", "Barangay West Kamias"
    
];


  // Select the barangay dropdown element
  const barangaySelect = document.getElementById('barangaySelect'); // Corrected name

  // Function to populate barangays
  function populateBarangays() {
      barangays.forEach(barangay => {
          console.log('Adding barangay:', barangay);  // Log to verify the loop
          const option = document.createElement("option");
          option.value = barangay;
          option.textContent = barangay;
          barangaySelect.appendChild(option);
      });
  }

  // Call the function to populate barangays after the DOM content is fully loaded
  populateBarangays();
});


