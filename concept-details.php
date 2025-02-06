<?php
require 'config.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

// Get the concept ID from the query string
$conceptId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the concept details
try {
    $stmt = $pdo->prepare("SELECT * FROM game_concepts WHERE id = ? AND user_id = ?");
    $stmt->execute([$conceptId, $_SESSION['user_id']]);
    $concept = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$concept) {
        echo "Concept not found or unauthorized access.";
        exit;
    }
} catch (PDOException $e) {
    echo "An error occurred while fetching the concept.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Concept Details</title>
  <style>
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
    .concept-detail {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
    }
    .concept-detail h2 {
      font-size: 1.5rem;
      margin: 0 0 1rem 0;
      color: #2c3e50;
    }
    .concept-detail p {
      font-size: 1rem;
      color: #6c757d;
      margin: 0.5rem 0;
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
  </style>
</head>
<body>
  <!-- Header -->
  <div class="header">
    <h1>Concept Details</h1>
    <div class="actions">
      <button onclick="window.location.href='home.php'">Back to Home</button>
      <button onclick="logout()">Logout</button>
    </div>
  </div>

  <!-- Container -->
  <div class="container">
    <div class="concept-detail">
      <h2><?php echo htmlspecialchars($concept['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
      <p><strong>Basic Information:</strong> <?php echo htmlspecialchars($concept['game_overview'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
      <p><strong>Gameplay Mechanics:</strong> <?php echo htmlspecialchars($concept['gameplay_mechanics'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
      <p><strong>Characters:</strong> <?php echo htmlspecialchars($concept['characters'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
      <p><strong>Weapons & Equipment:</strong> <?php echo htmlspecialchars($concept['weapons_equipment'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
      <p><strong>Maps & Environments:</strong> <?php echo htmlspecialchars($concept['maps_environments'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
      <p><strong>Story & Setting:</strong> <?php echo htmlspecialchars($concept['story_setting'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
      <p><strong>Monetization:</strong> <?php echo htmlspecialchars($concept['monetization'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
      <p><strong>Technical Specifications:</strong> <?php echo htmlspecialchars($concept['technical_specifications'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
      <p><strong>Cover Image:</strong></p>
      <?php if ($concept['image_url']): ?>
        <img src="<?php echo htmlspecialchars($concept['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Cover Image" style="max-width: 100%; height: auto; margin-top: 1rem;">
      <?php else: ?>
        <p>No cover image available.</p>
      <?php endif; ?>
    </div>
  </div>

  <script>
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
  </script>
</body>
</html>