import 'flowbite';

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-show-all').forEach(function (button) {
        if (button.disabled) return;
        var expanded = false;
        button.addEventListener('click', function () {
            var section = button.closest('section');
            var extras = section ? section.querySelectorAll('.scheme-extra') : [];
            expanded = !expanded;
            extras.forEach(function (el) {
                if (expanded) {
                    el.removeAttribute('hidden');
                } else {
                    el.setAttribute('hidden', '');
                }
            });
            button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            button.textContent = expanded ? 'Tampilkan Lebih Sedikit' : 'Lihat Semua Skema';
        });
    });
});