// the JavaScript search frontend Interaction

document.addEventListener('DOMContentLoaded', () => {
  // to retrieve the toplevel Dom elements: button, input, result
  const searchBtn = document.getElementById('findUniBtn');
  const searchInput = document.getElementById('searchField');
  const resultsBox = document.getElementById('searchResults');
  // if search button is present, listen for click event
  if (searchBtn) {
    searchBtn.addEventListener('click', (e) => {
      e.preventDefault();
      // Catch and sanitize search input
      const searchTerm = searchInput.value.trim();
      
      if (searchTerm === "") {
        resultsBox.innerHTML = `<p style="color: red;">Please enter a search term.</p>`;
        return;
      }
      
      //to show loading message while getting result
      resultsBox.innerHTML = `<p style="color: #004080;">Searching universities...</p>`;
      
      const apiURL = `https://jcollenette.linux.studentwebserver.co.uk/CO7006API/Universities.php?name=${encodeURIComponent(searchTerm)}`;
      // get result from external API with fetch
      fetch(apiURL)
        .then(response => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          // Filter for  UK universities  and to show them
          const filteredResults = data.filter(item => item.country === "United Kingdom");
          
          if (filteredResults.length === 0) {
            resultsBox.innerHTML = `<p>kindly note no UK universities found for "${searchTerm}".</p>`;
            return;
          }
          // to format and show result in styled layout
          const outputHTML = filteredResults.map(item => {
            return `
              <div class="result-card">
                <strong>${item.name}</strong><br/>
                <a href="${item.web_pages[0]}" target="_blank">${item.web_pages[0]}</a>
              </div>
            `;
          }).join('');
          
          resultsBox.innerHTML = `<div class="result-grid">${outputHTML}</div>`;
        })
        // to handle fetch and network error well
        .catch(err => {
          console.error("UniSearch fetch error:", err);
          resultsBox.innerHTML = `<p style="color: red;">so sorry Something went wrong. Please could you try again later.</p>`;
        });
    });
  }
});
