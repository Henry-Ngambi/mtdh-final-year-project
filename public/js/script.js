function loadPage(page) {  
    const contentDiv = document.getElementById('content');  
    
    // Use AJAX to load content without refreshing the page  
    fetch(page)  
        .then(response => {  
            if (!response.ok) {  
                throw new Error('Network response was not ok');  
            }  
            return response.text();  
        })  
        .then(html => {  
            contentDiv.innerHTML = html;  
            // Update the URL without reloading the page  
            history.pushState(null, '', page);  
        })  
        .catch(error => {  
            contentDiv.innerHTML = '<p>Error loading content.</p>';  
            console.error('There has been a problem with your fetch operation:', error);  
        });  
}  

// Handle back/forward buttons  
window.onpopstate = function() {  
    loadPage(location.pathname);  
};