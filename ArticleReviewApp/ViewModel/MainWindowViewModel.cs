using System.Windows;
using System.ComponentModel;
using ArticleReviewApp.Models;
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

        public int? SelectedArticleId {get; set;} = 0;

        public ObservableCollection<ArticleSummary> ArticleSummaries { get; set; } = new ();

        private ArticleRepo _repo = new();

        private Article? _currentArticle;

        public async Task LoadArticle(int id)
        {
            var article = await _repo.GetByIdAsync(id);
            _currentArticle = article;
            ArticleText = article?.Text ?? string.Empty;
            ArticleTitle = article?.Name ?? string.Empty;
            ArticleDescription = article?.Description ?? string.Empty;
            SelectedArticleId = article?.Id ?? 0;
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof(ArticleText)));
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof(ArticleTitle)));
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof(ArticleDescription)));
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(nameof(SelectedArticleId)));
        }

        public async Task LoadAllArticleSummaries()
        {
            var summaries = await _repo.GetAllSummariesAsync();
            ArticleSummaries.Clear();
            foreach (var summary in summaries)
                ArticleSummaries.Add(summary);
        }

        public async Task ApproveArticleAsync() => await SetStatusAsync("Accepted");

        public async Task RejectArticleAsync() => await SetStatusAsync("Rejected");

        private async Task SetStatusAsync(string status)
        {
            if (_currentArticle is null)
                return;

            _currentArticle.Name = ArticleTitle;
            _currentArticle.Description = ArticleDescription;
            _currentArticle.Text = ArticleText;
            _currentArticle.Status = status;

            await _repo.UpdateAsync(_currentArticle);
            await LoadAllArticleSummaries();
        }

        public async Task SaveArticleAsync()
        {
            if (_currentArticle is null)
                return;

            _currentArticle.Name = ArticleTitle;
            _currentArticle.Description = ArticleDescription;
            _currentArticle.Text = ArticleText;

            await _repo.UpdateAsync(_currentArticle);
            await LoadAllArticleSummaries();
        }

        public event PropertyChangedEventHandler? PropertyChanged;
    }
} 