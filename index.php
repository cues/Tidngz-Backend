<?php
require "headers.php";
require "response.php";
require "db_pdo.php";
require "data.php";

// GCS upload page (simple test UI)
$bucketName = 'tidngz';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	try {
		if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
			http_response_code(400);
			throw new RuntimeException('No file uploaded (or upload error).');
		}

		// Check for Composer autoloader in common Cloud Run paths
		$autoloadCandidates = [
			__DIR__ . '/vendor/autoload.php',
			'/workspace/vendor/autoload.php',
			'/app/vendor/autoload.php',
		];

		$autoloadPath = null;
		foreach ($autoloadCandidates as $p) {
			if (file_exists($p)) {
				$autoloadPath = $p;
				break;
			}
		}

		if (!$autoloadPath) {
			throw new RuntimeException("Composer dependencies missing. Cannot upload to GCS.");
		}

		require_once $autoloadPath;

		if (!class_exists(\Google\Cloud\Storage\StorageClient::class)) {
			throw new RuntimeException("Google Cloud Storage library not installed.");
		}

		$tmpPath = $_FILES['image']['tmp_name'];
		$origName = $_FILES['image']['name'] ?? 'upload';
		$mime = mime_content_type($tmpPath) ?: 'application/octet-stream';

		// Basic allowlist (adjust as needed)
		$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
		if (!in_array($mime, $allowed, true)) {
			http_response_code(415);
			throw new RuntimeException('Unsupported file type: ' . $mime);
		}

		// Object name: uploads/YYYYmmdd_HHMMSS_random_originalname
		$safeName = preg_replace('/[^a-zA-Z0-9._-]+/', '_', $origName);
		$objectName = 'uploads/' . gmdate('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '_' . $safeName;

		$storage = new Google\Cloud\Storage\StorageClient();
		$bucket = $storage->bucket($bucketName);

		$object = $bucket->upload(
			fopen($tmpPath, 'rb'),
			[
				'name' => $objectName,
				'metadata' => [
					'contentType' => $mime,
				],
			]
		);

		// If your bucket is public, this URL will work; otherwise use signed URLs.
		$publicUrl = 'https://storage.googleapis.com/' . $bucketName . '/' . $objectName;

		header('Content-Type: text/html; charset=utf-8');
		echo "<p>Uploaded OK</p>";
		echo "<p>Object: <code>" . htmlspecialchars($objectName) . "</code></p>";
		echo "<p>URL: <a href=\"" . htmlspecialchars($publicUrl) . "\" target=\"_blank\" rel=\"noreferrer\">" . htmlspecialchars($publicUrl) . "</a></p>";
		echo "<p><a href=\"/\">Upload another</a></p>";
		exit;
	} catch (Throwable $e) {
		error_log("Upload error: " . $e->getMessage());
		header('Content-Type: text/html; charset=utf-8');
		echo "<p>Upload failed: " . htmlspecialchars($e->getMessage()) . "</p>";
		echo "<p><a href=\"/\">Back</a></p>";
		exit;
	}
}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Tidngz - Upload to GCS</title>
</head>
<body>
	<h1>Tidngz - Upload image to bucket: <?php echo htmlspecialchars($bucketName); ?></h1>

	<form method="post" enctype="multipart/form-data">
		<label>
			Image:
			<input type="file" name="image" accept="image/*" required>
		</label>
		<button type="submit">Save</button>
	</form>
</body>
</html>

