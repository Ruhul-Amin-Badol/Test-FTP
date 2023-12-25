#!/bin/bash

# Step 1: Download changes from cPanel
# Use wget or curl to download files

# Step 2: Update local Git repository
git pull origin master  # or use the appropriate branch

# Step 3: Commit changes locally
git add .
git commit -m "Sync changes from cPanel"

# Step 4: Push changes to remote Git repository
git push origin master  # or use the appropriate branch