using System.Windows;
using System.ComponentModel;
using ArticleReviewApp.Repositories;

namespace ArticleReviewApp
{
    public partial class MainWindowViewModel : INotifyPropertyChanged
    {
        public string ArticleText { get; set; } = string.Empty;
        public string ArticleTitle { get; set; } = string.Empty;
        public string ArticleDescription { get; set; } = string.Empty;

        private ArticleRepo _repo =new();

        public async Task LoadArticle(int id)
        {
            var article = await _repo.GetByIdAsync(id);
            ArticleText = article?.Text ?? string.Empty;
            ArticleTitle = article?.Name ?? string.Empty;
            ArticleDescription = article?.Description ?? string.Empty;
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof(ArticleText)));
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof(ArticleTitle)));
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof(ArticleDescription)));
        }

        public async Task LoadAllArticles()
        {
            await _repo.GetAllAsync();
        }

        public event PropertyChangedEventHandler? PropertyChanged;
    }
} 