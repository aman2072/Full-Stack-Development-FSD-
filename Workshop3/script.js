// In-memory movie list
let allMovies = [];

// DOM elements
const movieListDiv = document.getElementById('movie-list');
const searchInput = document.getElementById('search-input');
const form = document.getElementById('add-movie-form');

// Render movies
function renderMovies(moviesToDisplay) {
    movieListDiv.innerHTML = '';

    if (moviesToDisplay.length === 0) {
        movieListDiv.innerHTML = '<p class="empty">No movies found.</p>';
        return;
    }

    moviesToDisplay.forEach(movie => {
        const movieElement = document.createElement('div');
        movieElement.classList.add('movie-item');

        movieElement.innerHTML = `
            <div>
                <h4>${movie.title}</h4>
                <p>${movie.genre} | ${movie.year}</p>
            </div>
            <div class="actions">
                <button class="edit" onclick="editMoviePrompt('${movie.id}')">Edit</button>
                <button class="delete" onclick="deleteMovie('${movie.id}')">Delete</button>
            </div>
        `;

        movieListDiv.appendChild(movieElement);
    });
}

// Initial render
renderMovies(allMovies);

// Search movies
searchInput.addEventListener('input', () => {
    const text = searchInput.value.toLowerCase();

    const filtered = allMovies.filter(movie =>
        movie.title.toLowerCase().includes(text) ||
        movie.genre.toLowerCase().includes(text)
    );

    renderMovies(filtered);
});

// Add movie
form.addEventListener('submit', event => {
    event.preventDefault();

    const newMovie = {
        id: crypto.randomUUID(),
        title: document.getElementById('title').value,
        genre: document.getElementById('genre').value,
        year: parseInt(document.getElementById('year').value)
    };

    allMovies.push(newMovie);
    form.reset();
    renderMovies(allMovies);
});

// Edit movie
function editMoviePrompt(id) {
    const movie = allMovies.find(m => m.id === id);
    if (!movie) return;

    const newTitle = prompt("Enter new title:", movie.title);
    const newYear = prompt("Enter new year:", movie.year);
    const newGenre = prompt("Enter new genre:", movie.genre);

    if (!newTitle || !newYear || !newGenre) return;
    if (isNaN(newYear)) {
        alert("Year must be a number");
        return;
    }

    movie.title = newTitle;
    movie.year = parseInt(newYear);
    movie.genre = newGenre;

    renderMovies(allMovies);
}

// Delete movie
function deleteMovie(id) {
    if (!confirm("Are you sure you want to delete this movie?")) return;
    allMovies = allMovies.filter(m => m.id !== id);
    renderMovies(allMovies);
}
