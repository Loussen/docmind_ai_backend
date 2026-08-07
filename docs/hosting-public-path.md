# Shared hosting: strip `/public` from public URLs

Behind Cloudflare / php-fpm, root `.htaccess` alone often still lets Laravel
answer `/public/...` with `200`. App middleware is the reliable fix.

## Wired

`StripPublicUrlPrefix` is prepended in `bootstrap/app.php`. It 301s:

`https://docsmind.app/public/...` → `https://docsmind.app/...`

using `APP_URL` + path (not `redirect()->to()`, which would re-add `/public`).

```bash
php artisan optimize:clear
curl -sI 'https://docsmind.app/public/articles?n=1' | egrep -i 'HTTP/|location'
# expect: 301 + location: https://docsmind.app/articles?n=1
```

Optional root rewrite: `htaccess.root.example` (next to `public/`, not inside it).

Never create route-named folders under `public/` (e.g. `public/articles/`).
