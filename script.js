function signup() {
    const name = document.getElementById('signup-name').value;
    const email = document.getElementById('signup-email').value;
    const password = document.getElementById('signup-password').value;

    fetch('signup.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            toggleAuthForm(); // Switch to login form
        } else {
            alert(data.message);
        }
    });
}

function login() {
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    fetch('login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            checkSession();
        } else {
            alert(data.message);
        }
    });
}

function checkSession() {
    fetch('check-session.php')
        .then(response => response.json())
        .then(data => {
            if (data.isLoggedIn) {
                showApp();
            } else {
                document.getElementById('app').style.display = 'none';
                document.getElementById('auth-page').style.display = 'flex';
            }
        });
}

function logout() {
    fetch('logout.php')
        .then(() => {
            document.getElementById('app').style.display = 'none';
            document.getElementById('auth-page').style.display = 'flex';
        });
}

// Check session on page load
window.onload = checkSession;

function showApp() {
    document.getElementById('auth-page').style.display = 'none';
    document.getElementById('app').style.display = 'block';
    document.getElementById('logout-btn').style.display = 'inline-block';
}

const steps = document.querySelectorAll('.step');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const saveBtn = document.getElementById('save-btn');
    let currentStep = 0;

    // Show current step
    function showStep(stepIndex) {
      steps.forEach((step, index) => {
        step.classList.toggle('active', index === stepIndex);
        document.querySelector('.progress-bar span').classList.toggle('active', index === stepIndex);
      });
      prevBtn.disabled = stepIndex === 0;
      nextBtn.style.display = stepIndex < steps.length - 1 ? 'inline-block' : 'none';
      saveBtn.style.display = stepIndex === steps.length - 1 ? 'inline-block' : 'none';
    }

    // Next button
    nextBtn.addEventListener('click', () => {
      if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
      }
    });

    // Previous button
    prevBtn.addEventListener('click', () => {
      if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
      }
    });

    // Progress bar click
    document.querySelector('.progress-bar').addEventListener('click', (event) => {
      if (event.target.tagName === 'SPAN') {
        const stepIndex = Array.from(event.target.parentNode.children).indexOf(event.target);
        if (stepIndex <= currentStep + 1) {
          currentStep = stepIndex;
          showStep(currentStep);
        }
      }
    });

    // Save concept
    document.getElementById('concept-form').addEventListener('submit', function (event) {
      event.preventDefault();

      const title = document.getElementById('title').value;
      const gameOverview = document.getElementById('game-overview').value;
      const gameplayMechanics = document.getElementById('gameplay-mechanics').value;
      const characters = document.getElementById('characters').value;
      const weaponsEquipment = document.getElementById('weapons-equipment').value;
      const mapsEnvironments = document.getElementById('maps-environments').value;
      const storySetting = document.getElementById('story-setting').value;
      const monetization = document.getElementById('monetization').value;
      const technicalSpecifications = document.getElementById('technical-specifications').value;
      const fileInput = document.getElementById('image-upload');

      if (!title) {
        alert('Title is required!');
        return;
      }

      let imageUrl = '';
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
        });
      } else {
        saveConcept(title, gameOverview, gameplayMechanics, characters, weaponsEquipment, mapsEnvironments, storySetting, monetization, technicalSpecifications, imageUrl);
      }
    });

    // Save concept to backend
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
      .then(data => {
        if (data.success) {
          alert(data.message);
        } else {
          alert(data.message);
        }
      });
    }

    // Download concept as HTML
    function downloadConcept() {
      const fullPageContent = document.querySelector('.container').innerHTML;
      const blob = new Blob([fullPageContent], { type: 'text/html' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `game-concept-documentation.html`;
      a.click();
      URL.revokeObjectURL(url);
    }

    // Print concept
    function printConcept() {
      window.print();
    }

    // Initialize
    window.onload = () => showStep(0);