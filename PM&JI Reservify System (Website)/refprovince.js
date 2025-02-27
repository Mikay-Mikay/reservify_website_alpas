document.getElementById('region').addEventListener('change', function() {
    const regionCode = this.value;
    const provinceSelect = document.getElementById('province');
    
    // I-clear ang kasalukuyang laman
    provinceSelect.innerHTML = '<option value="">Select Province</option>';
  
    fetch('refprovince.json')
      .then(response => response.json())
      .then(data => {
        data.RECORDS.forEach(province => {
          if (province.regCode === regionCode) {
            const option = document.createElement('option');
            option.value = province.provCode; // o kung nais mo, gamitin ang psgcCode
            option.textContent = province.provDesc;
            provinceSelect.appendChild(option);
          }
        });
      })
      .catch(error => console.error('Error loading province data:', error));
  });
  