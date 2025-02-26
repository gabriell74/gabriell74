
// Function sidebar untuk click 
   function openSidebar() {
      document.getElementById("sidebar").style.width = "250px"; // Buka sidebar
      document.querySelector(".content").style.marginLeft = "250px"; // Pindahkan konten utama
      document.querySelector(".navbar").style.marginLeft = "250px"; // Pindahkan navbar
    }
    
    function closeSidebar() {
      document.getElementById("sidebar").style.width = "0"; // Tutup sidebar
      document.querySelector(".content").style.marginLeft = "0"; // Reset margin konten utama
      document.querySelector(".navbar").style.marginLeft = "0"; // Reset margin navbar
    }

    // Script data table
      $(document).ready( function () {
        $('#data-table').DataTable();
    } );

    // Function untuk confirm ingin menghapus
      function confirmAction() {
      confirm("Apakah Anda yakin ingin menghapus?")
      }