<?php
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class Spaces {
    private $client;

    public function __construct() {
        if (!class_exists('Aws\S3\S3Client')) {
            throw new Exception("AWS SDK not loaded. Please ensure composer dependencies are installed.");
        }

        $this->client = new S3Client([
            'version' => 'latest',
            'region'  => defined('DO_SPACES_REGION') ? DO_SPACES_REGION : '',
            'endpoint' => defined('DO_SPACES_ENDPOINT') ? DO_SPACES_ENDPOINT : '',
            'credentials' => [
                'key'    => defined('DO_SPACES_KEY') ? DO_SPACES_KEY : '',
                'secret' => defined('DO_SPACES_SECRET') ? DO_SPACES_SECRET : '',
            ],
            'use_path_style_endpoint' => false
        ]);
    }

    /**
     * Uploads a file to DigitalOcean Spaces and returns the CDN URL.
     *
     * @param string $sourcePath The temporary file path (e.g., $_FILES['file']['tmp_name'])
     * @param string $filename The destination filename
     * @param string $folder The folder in the bucket (e.g., 'insights' or 'events')
     * @return string|false The CDN URL on success, false on failure
     */
    public function uploadImage($sourcePath, $filename, $folder) {
        $bucket = defined('DO_SPACES_BUCKET') ? DO_SPACES_BUCKET : '';
        $key = ltrim($folder . '/' . $filename, '/');
        
        // Determine mime type
        $mimeType = mime_content_type($sourcePath);
        if (!$mimeType) {
            $mimeType = 'application/octet-stream';
        }

        try {
            $result = $this->client->putObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'SourceFile' => $sourcePath,
                'ACL'    => 'public-read',
                'ContentType' => $mimeType
            ]);

            // Construct and return CDN URL
            $cdnEndpoint = defined('DO_SPACES_CDN_ENDPOINT') ? rtrim(DO_SPACES_CDN_ENDPOINT, '/') : '';
            if (!empty($cdnEndpoint) && $cdnEndpoint !== 'https://YOUR_DO_SPACES_BUCKET_HERE.YOUR_DO_SPACES_REGION_HERE.cdn.digitaloceanspaces.com') {
                return $cdnEndpoint . '/' . $key;
            } else {
                // Fallback to the S3 URL if CDN endpoint isn't correctly configured
                return $result['ObjectURL'];
            }
        } catch (AwsException $e) {
            // Log error
            error_log("Spaces Upload Error: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Spaces General Error: " . $e->getMessage());
            return false;
        }
    }
}
