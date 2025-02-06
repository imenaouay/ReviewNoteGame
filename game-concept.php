<?php
// Prevent session auto-closing
session_set_cookie_params([
    'lifetime' => 86400, // Session lifetime (e.g., 24 hours)
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);
// Start the session
session_start();
// Prevent session expiration while the user is active
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 86400)) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity timestamp
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html'); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Concept Documentation</title>
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
    .progress-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding: 1rem;
      background: #f9fafb;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .progress-bar span {
      flex: 1;
      text-align: center;
      padding: 0.5rem;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s ease;
      background: #eef2f7;
      color: #6c757d;
      font-weight: 500;
    }
    .progress-bar span.active,
    .progress-bar span:hover {
      background: #2c3e50;
      color: white;
    }
    .step {
      display: none;
      padding: 2rem;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
    }
    .step.active {
      display: block;
    }
    .form-group {
      margin-bottom: 1.5rem;
    }
    label {
      font-weight: bold;
      color: #6c757d;
    }
    input, textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
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
    <h1>Game Concept Documentation</h1>
    <div class="actions">
      <button onclick="downloadConcept()">Download Documentation</button>
      <button onclick="printConcept()">Print Documentation</button>
      <button onclick="logout()">Logout</button>
      <button onclick="window.location.href='home.php'">Back</button>
    </div>
  </div>

  <!-- Container -->
  <div class="container">
    <!-- Progress Bar -->
    <div class="progress-bar">
      <span class="active">Step 1</span>
      <span>Step 2</span>
      <span>Step 3</span>
      <span>Step 4</span>
      <span>Step 5</span>
      <span>Step 6</span>
      <span>Step 7</span>
      <span>Step 8</span>
    </div>

    <!-- Form Steps -->
    <form id="concept-form">
      <!-- Step 1: Game Overview -->
      <div class="step active">
        <h2>Step 1: Game Overview</h2>
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" id="title" required>
        </div>
        <div class="form-group">
          <label for="game-overview">Basic Information</label>
          <textarea id="game-overview" rows="5" required></textarea>
        </div>
      </div>

      <!-- Step 2: Gameplay Mechanics -->
      <div class="step">
        <h2>Step 2: Gameplay Mechanics</h2>
        <div class="form-group">
          <label for="gameplay-mechanics">Core Gameplay Systems</label>
          <textarea id="gameplay-mechanics" rows="5" required></textarea>
        </div>
      </div>

      <!-- Step 3: Characters -->
      <div class="step">
        <h2>Step 3: Characters</h2>
        <div class="form-group">
          <label for="characters">Character Profiles</label>
          <textarea id="characters" rows="5" required></textarea>
        </div>
      </div>

      <!-- Step 4: Weapons & Equipment -->
      <div class="step">
        <h2>Step 4: Weapons & Equipment</h2>
        <div class="form-group">
          <label for="weapons-equipment">Arsenal and Gear</label>
          <textarea id="weapons-equipment" rows="5" required></textarea>
        </div>
      </div>

      <!-- Step 5: Maps & Environments -->
      <div class="step">
        <h2>Step 5: Maps & Environments</h2>
        <div class="form-group">
          <label for="maps-environments">Level Design</label>
          <textarea id="maps-environments" rows="5" required></textarea>
        </div>
      </div>

      <!-- Step 6: Story & Setting -->
      <div class="step">
        <h2>Step 6: Story & Setting</h2>
        <div class="form-group">
          <label for="story-setting">Narrative Elements</label>
          <textarea id="story-setting" rows="5" required></textarea>
        </div>
      </div>

      <!-- Step 7: Monetization -->
      <div class="step">
        <h2>Step 7: Monetization</h2>
        <div class="form-group">
          <label for="monetization">Business Model</label>
          <textarea id="monetization" rows="5" required></textarea>
        </div>
      </div>

      <!-- Step 8: Technical Specifications -->
      <div class="step">
        <h2>Step 8: Technical Specifications</h2>
        <div class="form-group">
          <label for="technical-specifications">Platform and Engine</label>
          <textarea id="technical-specifications" rows="5" required></textarea>
        </div>
        <div class="form-group">
          <label for="image-upload">Upload Cover Image</label>
          <input type="file" id="image-upload">
        </div>
      </div>

      <!-- Actions -->
      <div class="actions">
        <button type="button" id="prev-btn" disabled>Previous</button>
        <button type="button" id="next-btn">Next</button>
        <button type="submit" id="save-btn" style="display: none;">Save Concept</button>
      </div>
    </form>
  </div>

  <script>
    // Multi-step form logic
    const steps = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const saveBtn = document.getElementById('save-btn');
    let currentStep = 0;

    // Function to show the current step
    function showStep(stepIndex) {
      // Toggle active class on steps
      steps.forEach((step, index) => {
        step.classList.toggle('active', index === stepIndex);
        document.querySelectorAll('.progress-bar span')[index].classList.toggle('active', index === stepIndex);
      });

      // Enable/Disable Previous and Next buttons
      prevBtn.disabled = stepIndex === 0; // Disable Previous on the first step
      nextBtn.style.display = stepIndex < steps.length - 1 ? 'inline-block' : 'none'; // Hide Next on the last step
      saveBtn.style.display = stepIndex === steps.length - 1 ? 'inline-block' : 'none'; // Show Save on the last step
    }

    // Next Button Logic
    nextBtn.addEventListener('click', () => {
      if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
      }
    });

    // Previous Button Logic
    prevBtn.addEventListener('click', () => {
      if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
      }
    });

    // Progress Bar Click Logic
    document.querySelector('.progress-bar').addEventListener('click', (event) => {
      if (event.target.tagName === 'SPAN') {
        const stepIndex = Array.from(event.target.parentNode.children).indexOf(event.target);
        if (stepIndex <= currentStep + 1) { // Allow navigating only up to the next step
          currentStep = stepIndex;
          showStep(currentStep);
        }
      }
    });

    // Form Submission Logic
    document.getElementById('concept-form').addEventListener('submit', function (event) {
    event.preventDefault();

    // Collect form data
    const title = document.getElementById('title')?.value || '';
    const gameOverview = document.getElementById('game-overview')?.value || '';
    const gameplayMechanics = document.getElementById('gameplay-mechanics')?.value || '';
    const characters = document.getElementById('characters')?.value || '';
    const weaponsEquipment = document.getElementById('weapons-equipment')?.value || '';
    const mapsEnvironments = document.getElementById('maps-environments')?.value || '';
    const storySetting = document.getElementById('story-setting')?.value || '';
    const monetization = document.getElementById('monetization')?.value || '';
    const technicalSpecifications = document.getElementById('technical-specifications')?.value || '';
    const fileInput = document.getElementById('image-upload');

    // Validate title
    if (!title) {
        alert('Title is required!');
        return;
    }

    let imageUrl = '';

    // Handle image upload if a file is selected
    if (fileInput.files.length > 0) {
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);

        fetch('upload-image.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                imageUrl = data.url;
                saveConcept(title, gameOverview, gameplayMechanics, characters, weaponsEquipment, mapsEnvironments, storySetting, monetization, technicalSpecifications, imageUrl);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error uploading image:', error);
            alert('An error occurred while uploading the image. Please try again.');
        });
    } else {
        // If no image is uploaded, proceed directly to saving the concept
        saveConcept(title, gameOverview, gameplayMechanics, characters, weaponsEquipment, mapsEnvironments, storySetting, monetization, technicalSpecifications, imageUrl);
    }
});

// Function to save the concept data to the backend
function saveConcept(title, gameOverview, gameplayMechanics, characters, weaponsEquipment, mapsEnvironments, storySetting, monetization, technicalSpecifications, imageUrl) {
    const data = {
        title,
        gameOverview,
        gameplayMechanics,
        characters,
        weaponsEquipment,
        mapsEnvironments,
        storySetting,
        monetization,
        technicalSpecifications,
        imageUrl
    };

    fetch('save-concept.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(result.message); // Notify the user that the concept was saved
            window.location.href = 'home.php'; // Optionally redirect to the login page or another page
        } else {
            alert(result.message); // Notify the user of an error
        }
    })
    .catch(error => {
        console.error('Error saving concept:', error);
        alert('An error occurred while saving the concept. Please try again.');
    });
}
    // Download Concept Documentation
    function downloadConcept() {
      fetch('get-concepts.php')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const conceptsHtml = data.concepts.map(concept => `
              <div class="concept-card">
                <h3>${concept.title}</h3>
                <p>${concept.game_overview}</p>
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

    // Print Concept Documentation
    function printConcept() {
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

    // Initialize the form
    window.onload = () => showStep(0); // Start with the first step
  </script>
</body>
</html>