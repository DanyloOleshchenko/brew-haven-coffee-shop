

document.addEventListener('DOMContentLoaded', function() {
  const summaryBody = document.getElementById('summaryBody');
  const totalAmountElement = document.getElementById('totalAmount');

  if (!summaryBody || !totalAmountElement) return;

  function updateOrderSummary() {
    summaryBody.innerHTML = '';
    let total = 0;

    document.querySelectorAll('.quantity-input').forEach(input => {
      const qty = parseInt(input.value, 10) || 0;
      if (qty > 0) {
        const name = input.dataset.name;
        const price = parseFloat(input.dataset.price);
        const lineTotal = qty * price;
        total += lineTotal;

        const row = document.createElement('tr');
        [name, qty, '$' + price.toFixed(2), '$' + lineTotal.toFixed(2)]
          .forEach(value => {
            const cell = document.createElement('td');
            cell.textContent = String(value);
            row.appendChild(cell);
          });
        summaryBody.appendChild(row);
      }
    });

    totalAmountElement.textContent = '$' + total.toFixed(2);
  }

  document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.productId;
      const input = document.getElementById('qty-' + id);
      if (!input) return;
      input.value = (parseInt(input.value, 10) || 0) + 1;
      updateOrderSummary();
    });
  });

  document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.productId;
      const input = document.getElementById('qty-' + id);
      if (!input) return;
      const current = parseInt(input.value, 10) || 0;
      if (current > 0) {
        input.value = current - 1;
        updateOrderSummary();
      }
    });
  });

  document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', updateOrderSummary);
  });

  updateOrderSummary();
});
