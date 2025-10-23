import Chart from 'chart.js/auto';

export function renderRegistrationChart(canvasId, labels, values) {
  const ctx = document.getElementById(canvasId);
  return new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Registrations',
        data: values,
        borderWidth: 1
      }]
    },
    options: { 
      responsive: true, 
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true
        }
      },
      scales: {
        x: {
          grid: {
            display: false
          }
        },
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 2,
            callback: function(value) {
              return Number.isInteger(value) ? value : '';
            }
          }
        }
      }
    }
  });
}

export function renderFeeCollectionChart(canvasId, labels, values, month) {
  console.log('renderFeeCollectionChart called with:', { canvasId, labels, values, month });
  
  const ctx = document.getElementById(canvasId);
  if (!ctx) {
    console.error('Canvas element not found:', canvasId);
    return null;
  }
  
  // Convert string values to numbers
  const numericValues = values.map(val => parseFloat(val) || 0);
  console.log('Converted numeric values:', numericValues);
  
  // Check if values array has any data
  const totalAmount = numericValues.reduce((a, b) => a + b, 0);
  console.log('Total amount:', totalAmount);
  
  // If no data, show a message
  if (totalAmount === 0) {
    ctx.getContext('2d').clearRect(0, 0, ctx.width, ctx.height);
    const context = ctx.getContext('2d');
    context.font = '16px Arial';
    context.textAlign = 'center';
    context.fillText('No fee data for current month', ctx.width / 2, ctx.height / 2);
    return null;
  }
  
  try {
    return new Chart(ctx, {
      type: 'pie',
      data: {
        labels,
        datasets: [{
          label: `Fee Collection Status`,
          data: numericValues,
          backgroundColor: [
            '#80F280', // Green for paid
            '#F29380'  // Red for unpaid
          ],
          borderColor: [
            '#80F280',
            '#F29380'
          ],
          borderWidth: 1
        }]
      },
      options: { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                return `${label}: (${percentage}%)`;  //₹${value.toFixed(2)}
              }
            }
          }
        }
      }
    });
  } catch (error) {
    console.error('Error creating chart:', error);
    return null;
  }
}

// Render member trainer allotment chart
export function renderMemberTrainerChart(canvasId, labels, values) {
  const ctx = document.getElementById(canvasId);
  if (ctx) {
    return new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'No. of members allotted',
          data: values,
          borderWidth: 1
        }]
      },
      options: { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            }
          },
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              callback: function(value) {
                return Number.isInteger(value) ? value : '';
              }
            }
          }
        }
      }
    });
  } else {
    console.error('Canvas element not found:', canvasId);
    return null;
  }
}

export function renderTodayAttendanceChart(canvasId, labels, values) {
  console.log('renderTodayAttendanceChart called with:', { canvasId, labels, values});

  const ctx = document.getElementById(canvasId);
  if (!ctx) {
    console.error('Canvas element not found:', canvasId);
    return null;
  }
  
  // Convert string values to numbers
  const numericValues = values.map(val => parseFloat(val) || 0);
  console.log('Converted numeric values:', numericValues);
  
  // Check if values array has any data
  const totalAmount = numericValues.reduce((a, b) => a + b, 0);
  console.log('Total amount:', totalAmount);
  
  // If no data, show a message
  if (totalAmount === 0) {
    ctx.getContext('2d').clearRect(0, 0, ctx.width, ctx.height);
    const context = ctx.getContext('2d');
    context.font = '16px Arial';
    context.textAlign = 'center';
    context.fillText('No attendance data for today', ctx.width / 2, ctx.height / 2);
    return null;
  }
  
  try {
    return new Chart(ctx, {
      type: 'pie',
      data: {
        labels,
        datasets: [{
          label: `Attendance`,
          data: numericValues,
          backgroundColor: [
            '#80F280', // Green for present
            '#F29380'  // Red for absent
          ],
          borderColor: [
            '#80F280',
            '#F29380'
          ],
          borderWidth: 1
        }]
      },
      options: { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                return `${label}: (${percentage}%)`;  //₹${value.toFixed(2)}
              }
            }
          }
        }
      }
    });
  } catch (error) {
    console.error('Error creating chart:', error);
    return null;
  }
}

// Simple member health progress line chart
export function renderMemberProgressChart(canvasId, measure_dates, measurement_values, metric) {
  console.log('renderMemberProgressChart called with:', { canvasId, measure_dates, measurement_values });
  
  const ctx = document.getElementById(canvasId);
  if (!ctx) {
    console.error('Canvas element not found:', canvasId);
    return null;
  }
  
  if (!measure_dates || !measurement_values || measure_dates.length === 0 || measurement_values.length === 0) {
    console.log('No data available for progress chart');
    ctx.getContext('2d').clearRect(0, 0, ctx.width, ctx.height);
    const context = ctx.getContext('2d');
    context.font = '16px Arial';
    context.textAlign = 'center';
    context.fillText('No progress data available', ctx.width / 2, ctx.height / 2);
    return null;
  }

  try {
    return new Chart(ctx, {
      type: 'line',
      data: {
        labels: measure_dates,
        datasets: [{
          label: `${metric.charAt(0).toUpperCase() + metric.slice(1)}`,
          data: measurement_values,
          borderColor: '#3B82F6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.8,
          pointBackgroundColor: '#3B82F6',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 2,
          pointRadius: 4,
          cubicInterpolationMode: 'monotone'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
          padding: {
            left: 5,
            right: 10,
            top: 20,
            bottom: 20
          }
        },
        plugins: {
          title: { 
            display: true, 
            text: `Progress in ${metric.charAt(0).toUpperCase() + metric.slice(1)}`,
            font: {
              size: 16,
              weight: 'bold'
            }
          },
          legend: { 
            display: true, 
            position: 'top' 
          }
        },
        scales: {
          x: { 
            display: true, 
            title: { 
              display: true, 
              text: 'Measurement Date',
              font: {
                weight: 'bold'
              }
            },
            grid: {
              display: true,
              color: 'rgba(0, 0, 0, 0.1)'
            },
            ticks: {
              padding: 10
            },
            offset: true,
            bounds: 'data'
          },
          y: { 
            display: true, 
            beginAtZero: true,
            title: { 
              display: true, 
              text: `${metric.charAt(0).toUpperCase() + metric.slice(1)}`,
              font: {
                weight: 'bold'
              }
            },
            grid: {
              display: true,
              color: 'rgba(0, 0, 0, 0.1)'
            },
            ticks: {
              padding: 10
            }
          }
        },
        interaction: {
          intersect: false,
          mode: 'index'
        },
        elements: {
          point: {
            hoverRadius: 6
          }
        }
      }
    });
  } catch (error) {
    console.error('Error creating progress chart:', error);
    return null;
  }
}

export function renderFeeCollectionsChart(canvasId, labels, values, filter) {
  let x_title = 'Memberships';
  // if(filter.membership_id == 0) {
  //   x_title = 'Payment Type';
  // } else if(filter.membership_id == -1) {
  //   x_title = 'All Memberships';
  // } else if(filter.membership_id != 0 && filter.membership_id != -1) {
  //   x_title = 'Membership Type';
  // }
  const ctx = document.getElementById(canvasId);
  return new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Fee Collection',
        data: values,
        borderWidth: 1
      }]
    },
    options: { 
      responsive: true, 
      maintainAspectRatio: false,
      plugins: {
          title: { 
            display: true, 
            text: `Fee Collection for the month of ${filter.fee_month}`,
            font: {
              size: 16,
              weight: 'bold'
            }
          },
          legend: { 
            display: true, 
            position: 'top' 
          }
      },
      // plugins: {
      //   legend: {
      //     display: true
      //   }
      // },
      scales: {
        x: { 
          display: true, 
          title: { 
            display: true, 
            text: `${x_title}`,
            font: {
              weight: 'bold'
            }
          },
          grid: {
            display: true,
            color: 'rgba(0, 0, 0, 0.1)'
          },
          ticks: {
            padding: 10
          },
          offset: true,
          bounds: 'data'
        },
        y: { 
          display: true, 
          beginAtZero: true,
          title: { 
            display: true, 
            text: 'Total Amount',
            font: {
              weight: 'bold'
            }
          },
          grid: {
            display: true,
            color: 'rgba(0, 0, 0, 0.1)'
          },
          ticks: {
            padding: 10
          }
        }
      }
    }
  });
}