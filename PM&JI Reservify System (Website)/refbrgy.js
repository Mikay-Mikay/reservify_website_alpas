document.getElementById('city').addEventListener('change', function() {
    const citymunCode = this.value;
    const brgySelect = document.getElementById('barangay');
    
    // I-clear ang kasalukuyang laman
    brgySelect.innerHTML = '<option value="">Select Barangay</option>';
  
    fetch('refbrgy.json')
      .then(response => response.json())
      .then(data => {
        data.RECORDS.forEach(brgy => {
          if (brgy.citymunCode === citymunCode) {
            const option = document.createElement('option');
            option.value = brgy.brgyCode;
            option.textContent = brgy.brgyDesc;
            brgySelect.appendChild(option);
          }
        });
      })
      .catch(error => console.error('Error loading barangay data:', error));
  });
  