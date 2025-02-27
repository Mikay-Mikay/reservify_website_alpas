document.getElementById('province').addEventListener('change', function() {
    const provCode = this.value;
    const citySelect = document.getElementById('city');
    
    // I-clear ang kasalukuyang laman
    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
  
    fetch('refcitymun.json')
      .then(response => response.json())
      .then(data => {
        data.RECORDS.forEach(city => {
          if (city.provCode === provCode) {
            const option = document.createElement('option');
            option.value = city.citymunCode;  // maaaring gamitin ang psgcCode kung gusto
            option.textContent = city.citymunDesc;
            citySelect.appendChild(option);
          }
        });
      })
      .catch(error => console.error('Error loading city/municipality data:', error));
  });
  