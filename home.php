<?php
// Start session management
session_set_cookie_params([
    'lifetime' => 86400, // Session lifetime (24 hours)
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Game Concept Documentation</title>
  <style>
    /* General Styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9fafb;
    }
    .header {
      background: linear-gradient(135deg, #4c6ef5, #2c3e50);
      color: white;
      padding: 1rem 2rem;
      text-align: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .header h1 {
      font-size: 2rem;
      margin: 0;
      font-weight: bold;
    }
    .header .actions {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 1rem;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 2rem 1rem;
    }
    .create-btn {
      display: block;
      margin: 0 auto 2rem auto;
      padding: 1rem 2rem;
      background: #2c3e50;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .create-btn:hover {
      background: #4c6ef5;
    }
    .concept-card {
      background: white;
      padding: 1.5rem;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 1.5rem;
    }
    .concept-card h3 {
      font-size: 1.5rem;
      margin: 0 0 0.5rem 0;
      color: #2c3e50;
    }
    .concept-card p {
      font-size: 1rem;
      color: #6c757d;
      margin: 0 0 1rem 0;
    }
    .concept-card .view-details {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      background: #2c3e50;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .concept-card .view-details:hover {
      background: #4c6ef5;
    }
    .no-concepts {
      text-align: center;
      font-size: 1.25rem;
      color: #6c757d;
    }
    button {
      padding: 0.75rem 1.5rem;
      background: #2c3e50;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    button:hover {
      background: #4c6ef5;
    }
    .actions {
      display: flex;
      justify-content: space-between;
      gap: 1rem;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <div class="header">
    <h1>Home - Game Concept Documentation</h1>
    <div class="actions">
      <button onclick="downloadConcepts()">Download Concepts</button>
      <button onclick="printConcepts()">Print Concepts</button>
      <button onclick="logout()">Logout</button>
    </div>
  </div>

  <!-- Container -->
  <div class="container">
    <button class="create-btn" onclick="window.location.href='game-concept.php'">Create New Note Concept</button>

    <div id="concept-list"></div>

    <!-- No Concepts Message -->
    <div id="no-concepts" class="no-concepts" style="display: none;">
      No saved concepts yet. Click "Create New Note Concept" to get started!
    </div>
  </div>

  <script>
    // Fetch and display saved concepts
    function loadConcepts() {
      fetch('get-concepts.php')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const conceptList = document.getElementById('concept-list');
            const noConcepts = document.getElementById('no-concepts');

            if (data.concepts.length === 0) {
              noConcepts.style.display = 'block';
              conceptList.innerHTML = '';
            } else {
              noConcepts.style.display = 'none';
              conceptList.innerHTML = data.concepts.map(concept => `
                <div class="concept-card">
                  <h3>${concept.title}</h3>
                  <p><strong>Basic Information:</strong> ${concept.game_overview || 'N/A'}</p>
                  <p><strong>Saved At:</strong> ${new Date(concept.created_at).toLocaleString()}</p>
                  <button class="view-details" onclick="viewDetails(${concept.id})">View Details</button>
                </div>
              `).join('');
            }
          } else {
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Error fetching concepts:', error);
          alert('An error occurred while loading concepts. Please try again.');
        });
    }

    // View Details of a Concept
    function viewDetails(conceptId) {
      window.location.href = `concept-details.php?id=${conceptId}`;
    }

    // Download Concepts
    function downloadConcepts() {
      fetch('get-concepts.php')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const conceptsHtml = data.concepts.map(concept => `
              <div class="concept-card">
                <h3>${concept.title}</h3>
                <p><strong>Basic Information:</strong> ${concept.game_overview || 'N/A'}</p>
                <p><strong>Saved At:</strong> ${new Date(concept.created_at).toLocaleString()}</p>
              </div>
            `).join('');
            const blob = new Blob([conceptsHtml], { type: 'text/html' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'game-concepts.html';
            a.click();
            URL.revokeObjectURL(url);
          } else {
            alert(data.message);
          }
        });
    }

    // Print Concepts
    function printConcepts() {
      window.print();
    }

    // Logout Functionality
    function logout() {
      fetch('logout.php', {
          method: 'POST' // Use POST to avoid caching issues
      })
      .then(() => {
          window.location.href = 'index.html'; // Redirect to the login page
      })
      .catch(error => {
          console.error('Error during logout:', error);
          alert('An error occurred while logging out. Please try again.');
      });
    }

    // Load concepts when the page loads
    window.onload = loadConcepts;
  </script>
</body>
</html>