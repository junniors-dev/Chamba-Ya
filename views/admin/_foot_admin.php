    </main>

    <script>
        // Modales genéricos: [data-abre-modal="id"] abre, [data-cierra-modal] cierra.
        document.addEventListener('click', function (e) {
            var abre = e.target.closest('[data-abre-modal]');
            if (abre) {
                var m = document.getElementById(abre.getAttribute('data-abre-modal'));
                if (m) {
                    m.classList.add('abierto');
                    // rellena campos del modal desde data-* del botón
                    Object.keys(abre.dataset).forEach(function (k) {
                        if (k.indexOf('campo') === 0) {
                            var target = m.querySelector('[name="' + abre.dataset[k].split('|')[0] + '"]');
                            if (target) target.value = abre.dataset[k].split('|')[1] || '';
                        }
                    });
                }
            }
            if (e.target.closest('[data-cierra-modal]') || e.target.classList.contains('admin-modal-overlay')) {
                document.querySelectorAll('.admin-modal-overlay.abierto').forEach(function (m) { m.classList.remove('abierto'); });
            }
        });
    </script>
</body>
</html>
