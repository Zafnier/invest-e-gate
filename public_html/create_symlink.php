<?php
// Set the paths for the source (vendor_files) and link
$target = '/home/inverszu/publich_html/storage/vendor_files'; // Target folder
$link = '/home/inverszu/public_html/vendor_files'; // Location of the symbolic link

// Create the symbolic link
if (!file_exists($link)) {
    if (symlink($target, $link)) {
        echo "Symbolic link created successfully!";
    } else {
        echo "Failed to create symbolic link.";
    }
} else {
    echo "The symbolic link already exists.";
}
?>
