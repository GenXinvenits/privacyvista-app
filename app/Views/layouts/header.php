<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'PrivacyVista'; ?></title>
    <script>
        (function(){
            try {
                var p = localStorage.getItem('privacyvista-theme') || 'system';
                var d = p === 'system' ? (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark') : p;
                document.documentElement.setAttribute('data-theme', d);
                document.documentElement.setAttribute('data-theme-preference', p);
            } catch(e) {}
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/app/public/assets/css/style.css?v=2">
    <link rel="stylesheet" href="/app/public/assets/css/forms.css?v=1">
    <link rel="stylesheet" href="/app/public/assets/css/reports.css?v=1">
    <link rel="stylesheet" href="/app/public/assets/css/settings.css?v=1">
    <link rel="stylesheet" href="/app/public/assets/css/theme-fix.css?v=1">
    <link rel="stylesheet" href="/app/public/assets/css/liquid-glass-cursor.css?v=1">
    <script src="/app/public/assets/js/theme.js?v=1" defer></script>
</head>
<body>
<div class="app-shell">
