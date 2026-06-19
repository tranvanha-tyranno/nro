# Teamobi Docker deploy

## 1. Chuan bi tren Ubuntu

```bash
sudo apt update
sudo apt install -y docker.io docker-compose-plugin
sudo systemctl enable --now docker
sudo usermod -aG docker $USER
```

Dang nhap lai shell sau khi add group `docker`.

## 2. Upload thu muc SRC len server

Vi du:

```bash
mkdir -p /opt/teamobi
cd /opt/teamobi
git clone <repo-cua-ban> .
cd SRC
```

Neu ban copy tay, chi can dua dung thu muc `SRC` len server roi `cd SRC`.

## 3. Tao file moi truong

```bash
cp .env.example .env
nano .env
```

Can sua it nhat:

- `SERVER_IP`: IP hoac domain ma client trong `gameLocal` se tro toi. Neu ban dung public host la `nro.luminostech.tech` thi de gia tri nay la `nro.luminostech.tech`.
- `DB_PASSWORD`
- `DB_ROOT_PASSWORD`

## 4. Chay bang 1 lenh

```bash
docker compose up -d --build
```

## 5. Xem log

```bash
docker compose logs -f game
docker compose logs -f db
```

## 6. Dung / khoi dong lai

```bash
docker compose restart
docker compose down
docker compose down -v
```

`down -v` se xoa ca du lieu MySQL volume.

## Ghi chu cho gameLocal

`gameLocal` la client. Client do chi can tro dung `SERVER_IP:SERVER_PORT` cua server dang chay tren Ubuntu la vao game duoc.

Neu ban tach `gameLocal` ra khoi repo server, cac script doi host cho client nam trong `SRC/tools`.

## Ghi chu Cloudflare

- Origin service cua game tren may server van la `localhost:14445`.
- Client o ben ngoai khong duoc tro vao `localhost:14445`; client phai tro vao `nro.luminostech.tech:14445`.
- Neu ban muon client game ket noi raw TCP truc tiep qua Cloudflare, Spectrum la san pham Cloudflare danh cho TCP/UDP game server. Cloudflare Tunnel co ho tro arbitrary TCP, nhung thuong di kem `cloudflared`/Access o phia client, nen khong phai luc nao cung hop voi game client thong thuong. 
