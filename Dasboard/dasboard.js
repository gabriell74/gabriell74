Chart.register(ChartDataLabels);

  const labels = [];
  const stokMasuk = [];
  const stokKeluar = [];

  // Mengolah data yang diterima dari PHP
  data_bahan.forEach(item => {
    labels.push(item.nama_bahan_baku);
    stokMasuk.push(item.stok_masuk);
    stokKeluar.push(item.stok_keluar);
  });

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels, 
        datasets: [
          {
            label: 'Bahan Baku Masuk',
            data: stokMasuk,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
          },
          {
            label: 'Bahan Baku Keluar',
            data: stokKeluar,
            backgroundColor: 'rgba(153, 102, 255, 0.7)',
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
          },
          datalabels: {
            color: 'black',
            font: {
              weight: 'bold',
            },
            align: 'end', // Place labels at the end of the bar
            anchor: 'end', // Anchor labels at the end of the bars
            display: true, // Ensure labels are displayed
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              padding: 15, // Menambah jarak antara ticks dan label pada axis Y
            }
          },
          x: {
            beginAtZero: true,
            ticks: {
              padding: 5, // Menambah jarak antara ticks dan label pada axis X
            }
          }
        }
      }
    });
function openSidebar() {
  document.getElementById("sidebar").style.width = "250px"; // Buka sidebar
  document.querySelector(".main-content").style.marginLeft = "250px"; // Pindahkan konten utama
  document.querySelector(".navbar").style.marginLeft = "250px"; // Pindahkan navbar
}

function closeSidebar() {
  document.getElementById("sidebar").style.width = "0"; // Tutup sidebar
  document.querySelector(".main-content").style.marginLeft = "0"; // Reset margin konten utama
  document.querySelector(".navbar").style.marginLeft = "0"; // Reset margin navbar
}