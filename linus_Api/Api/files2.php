<?php

# reference autoload
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/vendor/autoload.php');

# imports
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;

## adds file to the storage. Usage: storageAddFile("myContainer", "C:\path\to\file.png", "filename-on-storage.png")
function storageAddFile($containerName, $file, $fileName)
{
    # Setup a specific instance of an Azure::Storage::Client
    $connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('STORAGE_ACCOUNT_NAME').";AccountKey=".getenv('STORAGE_ACCOUNT_KEY');
    // Create blob client.
    $blobClient = BlobRestProxy::createBlobService($connectionString);

    $handle = @fopen($file, "r");
    if($handle)
    {
        $options = new CreateBlockBlobOptions();
        $mime = NULL;

        try
        {
            // identify mime type
            $mimes = new \Mimey\MimeTypes;
            $mime = $mimes->getMimeType(pathinfo($fileName, PATHINFO_EXTENSION));
            // set content type
            $options->setContentType($mime);
        }
        catch ( Exception $e )
        {
            error_log("Failed to read mime from '".$file.": ". $e);
        } 

        try
        {
            if($mime)
            {
                $cacheTime = getCacheTimeByMimeType($mime);
                if($cacheTime)
                {
                    $options->setCacheControl("public, max-age=".$cacheTime);
                }
            }

            $blobClient->createBlockBlob($containerName, $fileName, $handle, $options);
        } catch ( Exception $e ) {
            error_log("Failed to upload file '".$file."' to storage: ". $e);
        } 

        @fclose($handle);
        return true;
    } else {        
        error_log("Failed to open file '".$file."' to upload to storage.");
        return false;
    }
}

## get cache time by mime type
function getCacheTimeByMimeType($mime)
{  
    $mime = strtolower($mime);

    $types = array(
        "application/json" => 604800,// 7 days
        "application/javascript" => 604800,// 7 days
        "application/xml" => 604800,// 7 days
        "application/xhtml+xml" => 604800,// 7 days
        "image/bmp" => 604800,// 7 days
        "image/gif" => 604800,// 7 days
        "image/jpeg" => 604800,// 7 days
        "image/png" => 604800,// 7 days
        "image/tiff" => 604800,// 7 days
        "image/svg+xml" => 604800,// 7 days
        "image/x-icon" => 604800,// 7 days
        "text/plain" => 604800, // 7 days
        "text/html" => 604800,// 7 days
        "text/css" => 604800,// 7 days
        "text/richtext" => 604800,// 7 days
        "text/xml" => 604800,// 7 days
    );

    // return value
    if(array_key_exists($mime, $types))
    {
        return $types[$mime];
    }

    return FALSE;
}

## removes file from the storage. Usage: storageAddFile("myContainer", "filename-on-storage.png")
function storageRemoveFile($containerName, $fileName)
{
    # Setup a specific instance of an Azure::Storage::Client
    $connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('STORAGE_ACCOUNT_NAME').";AccountKey=".getenv('STORAGE_ACCOUNT_KEY');
    // Create blob client.
    $blobClient = BlobRestProxy::createBlobService($connectionString);

    try
    {
        $blobClient->deleteBlob($containerName, $fileName);
    } catch ( Exception $e ) {
        error_log("Failed to delete file '".$fileName."' from storage");
    } 

    return true;
}
?>