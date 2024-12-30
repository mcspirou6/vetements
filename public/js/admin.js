// Admin Dashboard Scripts
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    const sidebarToggle = document.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('body').classList.toggle('sidebar-toggled');
            document.querySelector('.sidebar').classList.toggle('toggled');
        });
    }

    // Close sidebar when window is less than 768px
    window.addEventListener('resize', function() {
        if (window.innerWidth < 768) {
            document.querySelector('.sidebar').classList.add('toggled');
        }
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    document.querySelector('.sidebar').addEventListener('mousewheel', function(e) {
        if (window.innerWidth > 768) {
            const delta = e.wheelDelta || -e.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });

    // File input
    const fileInputs = document.querySelectorAll('.custom-file-input');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0].name;
            const label = e.target.nextElementSibling;
            label.innerText = fileName;
        });
    });

    // Tooltips
    const tooltips = document.querySelectorAll('[data-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });

    // DataTables initialization
    const dataTables = document.querySelectorAll('.datatable');
    dataTables.forEach(table => {
        $(table).DataTable({
            "language": {
                "lengthMenu": "Afficher _MENU_ éléments par page",
                "zeroRecords": "Aucun élément trouvé",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Aucun élément disponible",
                "infoFiltered": "(filtré parmi _MAX_ éléments au total)",
                "search": "Rechercher:",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                }
            }
        });
    });

    // Sweet Alert confirmations
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action ne peut pas être annulée !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Toggle switches for active status
    const toggleSwitches = document.querySelectorAll('.toggle-switch');
    toggleSwitches.forEach(switch => {
        switch.addEventListener('change', function(e) {
            const url = this.dataset.url;
            const value = this.checked;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ value: value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Statut mis à jour avec succès');
                } else {
                    toastr.error('Erreur lors de la mise à jour du statut');
                    this.checked = !value;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Erreur lors de la mise à jour du statut');
                this.checked = !value;
            });
        });
    });
});
