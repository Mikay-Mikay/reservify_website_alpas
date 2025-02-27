document.addEventListener('DOMContentLoaded', () => {
    const regionSelect = document.getElementById('region');
  
    fetch('refregion.json')
      .then(response => response.json())
      .then(data => {
        data.RECORDS.forEach(region => {
          const option = document.createElement('option');
          option.value = region.regCode;  // o kung nais mo ay ang psgcCode
          option.textContent = region.regDesc;
          regionSelect.appendChild(option);
        });
      })
      .catch(error => console.error('Error loading region data:', error));
  });
  