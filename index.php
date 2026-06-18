<?php

// Entry point for hosts (e.g. cPanel) where the document root cannot be
// pointed at /public. Delegates straight to the real front controller —
// __DIR__ inside the required file still resolves to /public, so all of
// its relative paths into /vendor and /bootstrap resolve correctly.
require __DIR__.'/public/index.php';
