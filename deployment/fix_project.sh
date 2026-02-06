#!/usr/bin/env bash
# ═══════════════════════════════════════════════════════════════════
# fix_project.sh — All-in-One Project Stabilizer
# Domaintik — Laravel 12.x
#
# Menyelesaikan:
#   1. Memastikan struktur folder komponen Blade ada
#   2. Memverifikasi semua file komponen ada
#   3. Memverifikasi icon definitions lengkap
#   4. Membersihkan semua cache Laravel
#
# Jalankan:
#   bash deployment/fix_project.sh                                  (lokal)
#   docker exec si-project-tik-app-dev bash deployment/fix_project.sh  (docker)
# ═══════════════════════════════════════════════════════════════════
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
VIEWS="$PROJECT_ROOT/resources/views"
COMP="$VIEWS/components/komponen"
PROVIDER="$PROJECT_ROOT/app/Providers/AppServiceProvider.php"

echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║  Domaintik — Project Fix & Stabilizer                       ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""

[[ -f "$PROJECT_ROOT/artisan" ]] || { echo "❌ artisan not found. Run from project root."; exit 1; }

ERRORS=0
FIXES=0

# ═══════════════════════════════════════════════════════════════
# STEP 1: Fix folder structure
# ═══════════════════════════════════════════════════════════════
echo "📁 Step 1: Folder structure..."

# If old top-level komponen/ exists, migrate it
if [[ -d "$VIEWS/komponen" && ! -d "$COMP" ]]; then
    echo "   ⚠️  Found old komponen/ at top level — migrating to components/komponen/..."
    mkdir -p "$COMP/ui" "$COMP/formulir" "$COMP/navigasi"
    cp -r "$VIEWS/komponen/ui/"*        "$COMP/ui/"        2>/dev/null || true
    cp -r "$VIEWS/komponen/formulir/"*  "$COMP/formulir/"  2>/dev/null || true
    cp -r "$VIEWS/komponen/navigasi/"*  "$COMP/navigasi/"  2>/dev/null || true
    rm -rf "$VIEWS/komponen"
    echo "   ✅ Migrated komponen/ → components/komponen/"
    FIXES=$((FIXES + 1))
elif [[ -d "$VIEWS/komponen" && -d "$COMP" ]]; then
    echo "   ⚠️  Both old komponen/ and new components/komponen/ exist — removing old one..."
    rm -rf "$VIEWS/komponen"
    FIXES=$((FIXES + 1))
fi

for dir in ui formulir navigasi; do
    target="$COMP/$dir"
    if [[ ! -d "$target" ]]; then
        mkdir -p "$target"
        echo "   ✅ Created: components/komponen/$dir/"
    else
        echo "   ✔  OK: components/komponen/$dir/"
    fi
done

# Also clean up old empty component directories
for dir in "$VIEWS/components/form" "$VIEWS/components/ui"; do
    if [[ -d "$dir" ]] && [[ -z "$(ls -A "$dir" 2>/dev/null)" ]]; then
        rmdir "$dir" && echo "   🗑  Removed empty: ${dir#$VIEWS/}"
    fi
done
echo ""

# ═══════════════════════════════════════════════════════════════
# STEP 2: Verify component files
# ═══════════════════════════════════════════════════════════════
echo "🔍 Step 2: Component files..."

check_file() {
    local fp="$1" label="$2"
    local rel="${fp#$VIEWS/}"
    if [[ -f "$fp" ]]; then
        echo "   ✔  $rel"
    else
        echo "   ❌ MISSING: $rel ($label)"
        ERRORS=$((ERRORS + 1))
    fi
}

check_file "$COMP/ui/kartu-statistik.blade.php"   "Stat card"
check_file "$COMP/ui/tabel.blade.php"              "Table wrapper"
check_file "$COMP/ui/badge-status.blade.php"       "Status badge"
check_file "$COMP/formulir/input.blade.php"        "Form input"
check_file "$COMP/formulir/select.blade.php"       "Form select"
check_file "$COMP/formulir/textarea.blade.php"     "Form textarea"
check_file "$COMP/formulir/bagian.blade.php"       "Form section"
check_file "$COMP/formulir/radio-group.blade.php"  "Radio group"
check_file "$COMP/navigasi/sidebar-item.blade.php" "Sidebar item"
check_file "$VIEWS/components/icon.blade.php"      "Icon SVG map"
check_file "$VIEWS/components/sidebar.blade.php"   "Sidebar layout"
check_file "$VIEWS/dashboard/index.blade.php"      "Dashboard view"
echo ""

# ═══════════════════════════════════════════════════════════════
# STEP 3: Verify AppServiceProvider
# ═══════════════════════════════════════════════════════════════
echo "⚙️  Step 3: AppServiceProvider check..."

# With components under components/komponen/, we do NOT need anonymousComponentPath
if grep -q "anonymousComponentPath" "$PROVIDER"; then
    echo "   ⚠️  anonymousComponentPath() found — this is WRONG when files are in components/komponen/"
    echo "      The registration creates a namespace prefix requiring :: syntax."
    echo "      Since files are now under components/komponen/, dot syntax works natively."
    echo "      REMOVING the registration line..."
    
    # Remove the Blade::anonymousComponentPath line
    sed -i '/Blade::anonymousComponentPath/d' "$PROVIDER"
    
    # Remove Blade import if nothing else uses it
    if ! grep -q "Blade::" "$PROVIDER"; then
        sed -i '/use Illuminate\\Support\\Facades\\Blade;/d' "$PROVIDER"
    fi
    
    FIXES=$((FIXES + 1))
    echo "   ✅ Fixed: Removed anonymousComponentPath (not needed)."
else
    echo "   ✅ No anonymousComponentPath present (correct for components/komponen/ layout)."
fi
echo ""

# ═══════════════════════════════════════════════════════════════
# STEP 4: Verify icon definitions
# ═══════════════════════════════════════════════════════════════
echo "🎨 Step 4: Icon definitions..."

ICON_FILE="$VIEWS/components/icon.blade.php"
for icon in clipboard-list user-check key logout plus-circle home users clock \
            document-text check-circle x-circle information-circle exclamation-circle \
            arrow-right arrow-left chevron-right shield-check plus server server-stack \
            globe-alt computer-desktop eye; do
    if grep -q "'$icon'" "$ICON_FILE" 2>/dev/null; then
        echo "   ✔  $icon"
    else
        echo "   ❌ MISSING: $icon"
        ERRORS=$((ERRORS + 1))
    fi
done
echo ""

# ═══════════════════════════════════════════════════════════════
# STEP 5: Clear all Laravel caches
# ═══════════════════════════════════════════════════════════════
echo "🔄 Step 5: Clearing Laravel caches..."

cd "$PROJECT_ROOT"
if command -v php &>/dev/null; then
    PHP_CMD="php"
else
    PHP_CMD="$(which php 2>/dev/null || echo php)"
fi

for cmd in view:clear config:clear route:clear event:clear; do
    $PHP_CMD artisan $cmd 2>/dev/null && echo "   ✅ $cmd" || echo "   ⚠️  $cmd failed"
done
echo ""

# ═══════════════════════════════════════════════════════════════
# STEP 6: Live render test
# ═══════════════════════════════════════════════════════════════
echo "🧪 Step 6: Component render test..."

TEST_RESULT=$($PHP_CMD artisan tinker --execute="
try {
    \$html = \Illuminate\Support\Facades\Blade::render('<x-komponen.ui.kartu-statistik judul=\"Test\" angka=\"42\" />');
    echo 'PASS:' . strlen(\$html);
} catch (\Exception \$e) {
    echo 'FAIL:' . \$e->getMessage();
}" 2>&1)

if [[ "$TEST_RESULT" == PASS:* ]]; then
    echo "   ✅ <x-komponen.ui.kartu-statistik> renders OK (${TEST_RESULT#PASS:} chars)"
else
    echo "   ❌ RENDER FAILED: ${TEST_RESULT#FAIL:}"
    ERRORS=$((ERRORS + 1))
fi
echo ""

# ═══════════════════════════════════════════════════════════════
# SUMMARY
# ═══════════════════════════════════════════════════════════════
echo "═══════════════════════════════════════════════════════════════"
if [[ $ERRORS -eq 0 ]]; then
    echo "✅ ALL CHECKS PASSED. $FIXES auto-fix(es) applied."
    echo ""
    echo "Struktur komponen yang benar:"
    echo "   resources/views/components/komponen/ui/          ← kartu-statistik, tabel, badge-status"
    echo "   resources/views/components/komponen/formulir/    ← input, select, textarea, bagian, radio-group"
    echo "   resources/views/components/komponen/navigasi/    ← sidebar-item"
    echo ""
    echo "Blade syntax (gunakan DOT, bukan ::)"
    echo "   <x-komponen.ui.kartu-statistik />   ← ✅ BENAR"
    echo "   <x-komponen::ui.kartu-statistik />  ← ❌ SALAH (namespace syntax)"
else
    echo "⚠️  $ERRORS error(s) found, $FIXES auto-fix(es) applied."
    echo "   Manual intervention may be needed for missing files/icons."
fi
echo ""
echo "🔗 Test: http://localhost:8090/dashboard"
echo "═══════════════════════════════════════════════════════════════"
