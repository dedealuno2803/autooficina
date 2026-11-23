// OficinaSAEP — scripts auxiliares
document.addEventListener("DOMContentLoaded", function () {
  // Confirmação simples em links de exclusão (se houver)
  document.querySelectorAll("[data-confirm]").forEach(function (el) {
    el.addEventListener("click", function (e) {
      if (!confirm(el.getAttribute("data-confirm") || "Confirmar ação?")) {
        e.preventDefault();
      }
    });
  });
});
