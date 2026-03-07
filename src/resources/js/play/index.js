console.log("play/index.js loaded");
function updateLabel(slot, value) {
  const label = document.querySelector(`[data-player-label="${slot}"]`);
  if (!label) return;
  label.textContent = value && value.trim() ? value.trim() : String(slot);
}

document.querySelectorAll("[data-player-input]").forEach((el) => {
  el.addEventListener("input", (e) => {
    const slot = e.target.getAttribute("data-player-input");
    updateLabel(slot, e.target.value);
  });
});

// ひとまず formation は値を読むだけ（配置変更は次ステップ）
document.querySelectorAll('input[name="formation"]').forEach((el) => {
  el.addEventListener("change", () => {
    const selected = document.querySelector('input[name="formation"]:checked')?.value;
    console.log("formation:", selected);
  });
});