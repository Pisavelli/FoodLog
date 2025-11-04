const currentPage = window.location.pathname.split('/').pop();
const products = JSON.parse(localStorage.getItem('products')) || [];
const container = document.getElementById('product-list');

products.forEach((product, index) => {
  const card = document.createElement('div');
  card.className = 'card';

  let cardHTML = `
    <img src="${product.imageUrl}" alt="${product.name}" />
    <h3>${product.name}</h3>
    <p>${product.description}</p>
    <p><strong>Validade:</strong> ${product.validity}</p>
    <p><strong>Quantidade:</strong> ${product.quantity}</p>
    <p><strong>Cadastrado em:</strong> ${product.cadastro}</p>
  `;

  // Só adiciona o botão de excluir se estiver na página "meus-produtos.html"
  if (currentPage === 'meus-produtos.html') {
    cardHTML += `<button class="delete-btn">Excluir</button>`;
  }

  // Botão de adicionar ao carrinho só em produtos-disponiveis.html
  if (currentPage === 'produtos-disponiveis.html') {
    cardHTML += `<button class="add-cart-btn">Adicionar ao carrinho</button>`;
  }

  card.innerHTML = cardHTML;

  // Só adiciona o evento de exclusão se estiver na página "meus-produtos.html"
  if (currentPage === 'meus-produtos.html') {
    const deleteButton = card.querySelector('.delete-btn');
    if (deleteButton) {
      deleteButton.addEventListener('click', () => {
        products.splice(index, 1);
        localStorage.setItem('products', JSON.stringify(products));
        card.remove();
      });
    }
  }
  
    // Evento de adicionar ao carrinho
  if (currentPage === 'produtos-disponiveis.html') {
    const addButton = card.querySelector('.add-cart-btn');
    if (addButton) {
      addButton.addEventListener('click', () => {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.push(product);
        localStorage.setItem('cart', JSON.stringify(cart));
        alert(`"${product.name}" foi adicionado ao carrinho!`);
      });
    }
  }



  container.appendChild(card);
});
