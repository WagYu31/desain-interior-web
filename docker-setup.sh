#!/bin/bash
# Script setup untuk menjalankan web desain interior dengan Docker
# Jalankan: bash docker-setup.sh

set -e

echo "=========================================="
echo "  Setup Docker - Desain Interior Web"
echo "=========================================="

# Warna untuk output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# 1. Copy .env.docker ke .env jika belum ada atau berbeda
echo -e "\n${YELLOW}[1/5] Menyiapkan file .env...${NC}"
cp .env.docker .env
echo -e "${GREEN}✓ File .env berhasil disiapkan untuk Docker${NC}"

# 2. Stop container yang mungkin masih berjalan
echo -e "\n${YELLOW}[2/5] Menghentikan container lama (jika ada)...${NC}"
docker compose down --remove-orphans 2>/dev/null || true
echo -e "${GREEN}✓ Container lama sudah dihentikan${NC}"

# 3. Build image Docker
echo -e "\n${YELLOW}[3/5] Build Docker image (ini mungkin memakan waktu beberapa menit)...${NC}"
docker compose build --no-cache
echo -e "${GREEN}✓ Docker image berhasil di-build${NC}"

# 4. Jalankan semua container
echo -e "\n${YELLOW}[4/5] Menjalankan semua container...${NC}"
docker compose up -d
echo -e "${GREEN}✓ Semua container berhasil dijalankan${NC}"

# 5. Tunggu MySQL siap
echo -e "\n${YELLOW}[5/5] Menunggu database siap...${NC}"
echo "Menunggu MySQL container sehat..."
MAX_TRIES=30
COUNT=0
until docker compose exec db mysqladmin ping -h localhost -uroot -proot --silent 2>/dev/null || [ $COUNT -eq $MAX_TRIES ]; do
    echo -n "."
    COUNT=$((COUNT + 1))
    sleep 3
done

if [ $COUNT -eq $MAX_TRIES ]; then
    echo -e "\n${RED}✗ MySQL tidak bisa terhubung setelah menunggu. Coba jalankan: docker compose logs db${NC}"
    exit 1
fi

echo -e "\n${GREEN}✓ Database MySQL sudah siap!${NC}"

# 6. Jalankan artisan commands
echo -e "\n${YELLOW}Menjalankan konfigurasi Laravel...${NC}"
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan storage:link
echo -e "${GREEN}✓ Konfigurasi Laravel selesai${NC}"

echo ""
echo "=========================================="
echo -e "${GREEN}  ✓ SETUP SELESAI!${NC}"
echo "=========================================="
echo ""
echo "Web tersedia di:"
echo -e "  ${GREEN}→ Aplikasi : http://localhost:8000${NC}"
echo -e "  ${GREEN}→ phpMyAdmin: http://localhost:8080${NC}"
echo "     (username: root, password: root)"
echo ""
echo "Perintah berguna:"
echo "  docker compose logs -f app    # Lihat log aplikasi"
echo "  docker compose logs -f db     # Lihat log database"
echo "  docker compose down           # Matikan semua container"
echo "  docker compose up -d          # Jalankan ulang container"
echo ""
