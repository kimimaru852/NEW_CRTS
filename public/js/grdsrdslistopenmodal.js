// =========================
// EDIT BUTTON CLICK
// =========================
document.addEventListener("click", function (e) {
    const btn = e.target.closest(".edit-btn");
    if (!btn) return;

    const data = JSON.parse(btn.dataset.row);

    window.dispatchEvent(
        new CustomEvent("open-edit-modal", {
            detail: data,
        }),
    );
});

