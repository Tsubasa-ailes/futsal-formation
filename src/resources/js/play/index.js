import html2canvas from "html2canvas";

const debugText = document.getElementById("debug-text");

function setDebug(message) {
  if (debugText) {
    debugText.textContent = "debug: " + message;
  }
}

function updateLabel(slot, value) {
  const label = document.querySelector(`[data-player-label="${slot}"]`);
  if (!label) return;
  label.textContent = value && value.trim() ? value.trim() : String(slot);
}

const formations = {
  "22": ["pos-gk", "pos-2", "pos-3", "pos-4", "pos-5"],
  "121": ["pos-gk", "pos-121-df", "pos-121-mf1", "pos-121-mf2", "pos-121-fw"],
  "31": ["pos-gk", "pos-31-df1", "pos-31-df2", "pos-31-df3", "pos-31-fw"]
};

function clearDraggedPosition(player) {
  player.style.left = "";
  player.style.top = "";
}

function applyFormation(type) {
  const players = document.querySelectorAll("[data-player-label]");

  if (!formations[type]) {
    setDebug("formation not found: " + type);
    return;
  }

  players.forEach((player, i) => {
    clearDraggedPosition(player);
    player.className = "p " + formations[type][i];
  });

  setDebug("applyFormation: " + type);
}

function resetFormationOnly() {
  const selected = document.querySelector('input[name="formation"]:checked')?.value || "22";
  applyFormation(selected);
  saveFormation(selected);
  setDebug("formation reset");
}

function resetPlayers() {
  document.querySelectorAll("[data-player-input]").forEach((input) => {
    input.value = "";
  });

  document.querySelectorAll("[data-player-label]").forEach((label, index) => {
    const slot = index + 1;
    label.textContent = String(slot);
    label.style.left = "";
    label.style.top = "";
  });

  localStorage.removeItem("tact_players");
  setDebug("players reset");
}

function savePlayers() {
  const players = {};

  document.querySelectorAll("[data-player-input]").forEach((input) => {
    const slot = input.getAttribute("data-player-input");
    players[slot] = input.value;
  });

  localStorage.setItem("tact_players", JSON.stringify(players));
}

function saveFormation(type) {
  localStorage.setItem("tact_formation", type);
}

function loadSavedData() {
  const savedPlayers = localStorage.getItem("tact_players");

  if (savedPlayers) {
    const players = JSON.parse(savedPlayers);

    Object.keys(players).forEach((slot) => {
      const input = document.querySelector(`[data-player-input="${slot}"]`);
      if (input) {
        input.value = players[slot];
        updateLabel(slot, players[slot]);
      }
    });
  }

  const savedFormation = localStorage.getItem("tact_formation");

  if (savedFormation) {
    const radio = document.querySelector(`input[name="formation"][value="${savedFormation}"]`);
    if (radio) {
      radio.checked = true;
      applyFormation(savedFormation);
    }
  } else {
    applyFormation("22");
  }
}

function setupInputEvents() {
  document.querySelectorAll("[data-player-input]").forEach((el) => {
    el.addEventListener("input", (e) => {
      const slot = e.target.getAttribute("data-player-input");
      updateLabel(slot, e.target.value);
      savePlayers();
      setDebug("player updated: " + slot);
    });
  });
}

function setupFormationEvents() {
  document.querySelectorAll('input[name="formation"]').forEach((el) => {
    el.addEventListener("change", () => {
      const selected = document.querySelector('input[name="formation"]:checked')?.value;
      applyFormation(selected);
      saveFormation(selected);
    });
  });
}

function setupResetEvents() {
  const resetBtn = document.getElementById("reset-btn");
  const formationResetBtn = document.getElementById("formation-reset-btn");

  if (resetBtn) {
    resetBtn.addEventListener("click", () => {
      if (confirm("メンバーをリセットしますか？")) {
        resetPlayers();
      }
    });
  }

  if (formationResetBtn) {
    formationResetBtn.addEventListener("click", () => {
      if (confirm("陣形を初期位置に戻しますか？")) {
        resetFormationOnly();
      }
    });
  }
}

function setupExport() {
  const exportBtn = document.getElementById("export-btn");

  if (!exportBtn) return;

  exportBtn.addEventListener("click", async () => {
    if (!confirm("画像を保存しますか？")) {
      setDebug("export canceled");
      return;
    }
    
    const target = document.getElementById("capture-area");
    if (!target) {
      setDebug("capture-area not found");
      return;
    }

    try {
      const canvas = await html2canvas(target, {
        backgroundColor: null,
        useCORS: true,
        scale: 2
      });

      const link = document.createElement("a");
      link.download = "formation.png";
      link.href = canvas.toDataURL("image/png");
      link.click();

      setDebug("export success");
    } catch (error) {
      console.error(error);
      setDebug("export failed");
      alert("画像保存に失敗しました。");
    }
  });
}

function setupDragAndDrop(court) {
  const players = document.querySelectorAll("[data-player-label]");

  let activePlayer = null;

  players.forEach((player) => {
    player.addEventListener("pointerdown", (e) => {
      e.preventDefault();
      activePlayer = player;
      activePlayer.classList.add("dragging");

      if (activePlayer.setPointerCapture) {
        activePlayer.setPointerCapture(e.pointerId);
      }
    });

    player.addEventListener("pointermove", (e) => {
      if (!activePlayer) return;

      const courtRect = court.getBoundingClientRect();

      let x = e.clientX - courtRect.left;
      let y = e.clientY - courtRect.top;

      const halfWidth = activePlayer.offsetWidth / 2;
      const halfHeight = activePlayer.offsetHeight / 2;

      const minX = halfWidth;
      const maxX = court.clientWidth - halfWidth;
      const minY = halfHeight;
      const maxY = court.clientHeight - halfHeight;

      x = Math.max(minX, Math.min(x, maxX));
      y = Math.max(minY, Math.min(y, maxY));

      activePlayer.style.left = `${x}px`;
      activePlayer.style.top = `${y}px`;
    });

    player.addEventListener("pointerup", () => {
      if (!activePlayer) return;
      activePlayer.classList.remove("dragging");
      activePlayer = null;
      setDebug("drag end");
    });

    player.addEventListener("pointercancel", () => {
      if (!activePlayer) return;
      activePlayer.classList.remove("dragging");
      activePlayer = null;
      setDebug("drag cancel");
    });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  setupInputEvents();
  setupFormationEvents();
  setupResetEvents();
  setupExport();
  loadSavedData();

  const court = document.getElementById("capture-area");
  if (court) {
    setupDragAndDrop(court);
  }

  setDebug("ready");
});