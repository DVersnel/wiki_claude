using System.Windows;
using System.ComponentModel;
using ArticleReviewApp.Repositories;
using System.Collections.ObjectModel;
using ArticleReviewApp.Models.Dtos;

namespace ArticleReviewApp
{
    public partial class MainWindowViewModel : INotifyPropertyChanged
    {
        public string ArticleText { get; set; } = string.Empty;
        public string ArticleTitle { get; set; } = string.Empty;
        public string ArticleDescription { get; set; } = string.Empty;

        public ObservableCollection<ArticleSummary> ArticleSummaries { get; set; } = new ();

        private ArticleRepo _repo = new();

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

        public async Task LoadAllArticleSummaries()
        {
            var summaries = await _repo.GetAllSummariesAsync();
            ArticleSummaries.Clear();
            foreach (var summary in summaries)
                ArticleSummaries.Add(summary);
        }

        public event PropertyChangedEventHandler? PropertyChanged;
    }
} 