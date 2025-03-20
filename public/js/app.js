$(document).ready(function() {
    const movies = [
        "Star Wars",
        "Star Wars: Episode V - The Empire Strikes Back",
        "Star Wars: Episode VI - Return of the Jedi",
        "Star Wars: Episode I - The Phantom Menace",
        "Star Wars: Episode II - Attack of the Clones",
        "Star Wars: Episode III - Revenge of the Sith"
    ];

    const apiKey = OMDB_API_KEY;
    const movieCards = $("#movieCards");
    const movieDetails = $("#movieDetails");

    let loadedMovies = [];

    if (movieCards.length) {
        loadMovies();

        $("#searchButton").on("click", filterMovies);
        $("#movieSearch").on("keyup", filterMovies);
        $("#resetButton").on("click", resetMovies);

        function filterMovies() {
            const searchTerm = $("#movieSearch").val().toLowerCase();
            movieCards.empty();

            loadedMovies
                .filter(movie => movie.Title.toLowerCase().includes(searchTerm))
                .forEach(movie => {
                    const card = `
                        <div class="movie-card">
                            <a href="movie-details.php?id=${movie.imdbID}">
                                <img src="${movie.Poster}" alt="${movie.Title}" onerror="this.src='https://via.placeholder.com/300x400?text=No+Image'">
                                <div class="movie-card-content">
                                    <h3 class="movie-card-title">${movie.Title}</h3>
                                </div>
                            </a>
                        </div>
                    `;
                    movieCards.append(card);
                });
        }

        function resetMovies() {
            $("#movieSearch").val('');
            movieCards.empty();
            
            loadedMovies.forEach(movie => {
                const card = `
                    <div class="movie-card">
                        <a href="movie-details.php?id=${movie.imdbID}">
                            <img src="${movie.Poster}" alt="${movie.Title}" onerror="this.src='https://via.placeholder.com/300x400?text=No+Image'">
                            <div class="movie-card-content">
                                <h3 class="movie-card-title">${movie.Title}</h3>
                            </div>
                        </a>
                    </div>
                `;
                movieCards.append(card);
            });
        }

        function loadMovies() {
            const totalMovies = movies.length;
            let loadedCount = 0;

            movies.forEach(movie => {
                $.ajax({
                    url: `http://www.omdbapi.com/?t=${encodeURIComponent(movie)}&apikey=${apiKey}`,
                    method: "GET",
                    success: function(data) {
                        if (data.Response === "True") {
                            loadedMovies.push(data);
                            const card = `
                                <div class="movie-card">
                                    <a href="movie-details.php?id=${data.imdbID}">
                                        <img src="${data.Poster}" alt="${data.Title}" onerror="this.src='https://via.placeholder.com/300x400?text=No+Image'">
                                        <div class="movie-card-content">
                                            <h3 class="movie-card-title">${data.Title}</h3>
                                        </div>
                                    </a>
                                </div>
                            `;
                            movieCards.append(card);
                        }
                        loadedCount++;
                        if (loadedCount === totalMovies) {
                            loadedMovies.sort((a, b) => a.Title.localeCompare(b.Title));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching movie data:", error);
                        loadedCount++;
                    }
                });
            });
        }
    }

    if (movieDetails.length) {
        const urlParams = new URLSearchParams(window.location.search);
        const movieId = urlParams.get('id');

        function getFavorites() {
            return JSON.parse(localStorage.getItem('favoriteMovies')) || [];
        }

        function isMovieFavorite(id) {
            return getFavorites().includes(id);
        }

        function toggleFavorite(id) {
            const favorites = getFavorites();
            const index = favorites.indexOf(id);
            
            if (index === -1) {
                favorites.push(id);
            } else {
                favorites.splice(index, 1);
            }
            
            localStorage.setItem('favoriteMovies', JSON.stringify(favorites));
            updateFavoriteButton(id);
        }

        function updateFavoriteButton(id) {
            const isFavorite = isMovieFavorite(id);
            const button = $('#favoriteButton');
            button.toggleClass('active', isFavorite);
            button.html(isFavorite ? '❤️' : '🤍');
        }

        if (movieId) {
            $.ajax({
                url: `http://www.omdbapi.com/?i=${movieId}&apikey=${apiKey}`,
                method: "GET",
                success: function(data) {
                    if (data.Response === "True") {
                        const detailsHtml = `
                            <a href="index.php" class="back-button">← Voltar</a>
                            <div class="movie-header">
                                <img src="${data.Poster}" alt="${data.Title}" class="movie-poster" onerror="this.src='https://via.placeholder.com/300x450?text=No+Image'">
                                <div class="movie-info">
                                    <h1 class="movie-title">${data.Title}</h1>
                                    <div class="movie-meta">
                                        <p><strong>Ano:</strong> ${data.Year}</p>
                                        <p><strong>Classificação:</strong> ${data.Rated}</p>
                                        <p><strong>Duração:</strong> ${data.Runtime}</p>
                                        <p><strong>Gênero:</strong> ${data.Genre}</p>
                                        <p><strong>Diretor:</strong> ${data.Director}</p>
                                        <p><strong>Roteirista:</strong> ${data.Writer}</p>
                                        <p><strong>Atores:</strong> ${data.Actors}</p>
                                        <p><strong>Metacritic:</strong> ${data.Metascore}</p>
                                    </div>
                                    <div class="movie-plot">
                                        <h3>Sinopse:</h3>
                                        <p>${data.Plot}</p>
                                    </div>
                                    <div class="movie-ratings">
                                        <h3>Avaliações:</h3>
                                        ${data.Ratings.map(rating => `
                                            <div class="rating-item">
                                                <strong>${rating.Source}:</strong> ${rating.Value}
                                            </div>
                                        `).join('')}
                                    </div>
                                    <div class="favorite-container">
                                        <button id="favoriteButton" class="favorite-button">🤍</button>
                                        <span>Favoritar filme</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        movieDetails.html(detailsHtml);
                        
                        updateFavoriteButton(movieId);
                        
                        $('#favoriteButton').on('click', function() {
                            toggleFavorite(movieId);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching movie details:", error);
                    movieDetails.html('<p>Erro ao carregar detalhes do filme.</p>');
                }
            });
        }
    }
});