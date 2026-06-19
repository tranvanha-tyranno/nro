# gameLocal client setup

Neu ban tach `gameLocal` ra khoi repo server, IP/domain cua client duoc luu o:

`%USERPROFILE%\AppData\LocalLow\XUNGLORDLOCAL\XUNGLORDLOCAL\NRlink3`

Trong `SRC/tools` da co san script de doi nhanh:

- `set-client-local.ps1`
- `set-client-public.ps1`
- `set-client-mixed.ps1`

Vi du:

```powershell
powershell -ExecutionPolicy Bypass -File .\tools\set-client-public.ps1
```

Mac dinh public host la `nro.luminostech.tech:14445`.

