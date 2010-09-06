#!/bin/bash
echo "This will make certain files and directory writable for the installer"
read -p "Press enter to continue..."
chmod a+w ../config.php
chmod a+w ../admin/config.php
chmod a+w ../cache/
chmod a+w ../image/
chmod a+w ../image/cache/
chmod a+w ../download/
echo "Done!";
echo
echo "Once the install is complete..."
read -p "Press enter to continue..."
chmod go-w ../config.php
chmod go-w ../admin/config.php
echo "Done!"
