using System.Collections.ObjectModel;
using ArticleReviewApp.Models;
using ArticleReviewApp.Models.Dtos;
using ArticleReviewApp.Repositories;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

namespace ArticleReviewApp
{
    public partial class MainWindowViewModel : ObservableObject
    {
        private readonly ArticleRepo _repo = new();
        private readonly User _currentUser;

        private Article? _currentArticle;

        public MainWindowViewModel(User currentUser)
        {
            _currentUser = currentUser;
        }

        public string Title => $"Article Review - {_currentUser.Name} ({_currentUser.Roles})";

        public ObservableCollection<ArticleSummary> ArticleSummaries { get; } = new();

        [ObservableProperty]
        public partial string ArticleText { get; set; } = string.Empty;

        [ObservableProperty]
        public partial string ArticleTitle { get; set; } = string.Empty;

        [ObservableProperty]
        public partial string ArticleDescription { get; set; } = string.Empty;

        [ObservableProperty]
        public partial int? SelectedArticleId { get; set; } = 0;

        [ObservableProperty]
        public partial ArticleSummary? SelectedArticle { get; set; }

        private bool HasArticle => _currentArticle is not null;

        // Selecting a row in the list loads that article into the editor.
        // Null happens when the list is refreshed; the editor keeps its article then.
        partial void OnSelectedArticleChanged(ArticleSummary? value)
        {
            if (value is not null)
                LoadArticleCommand.Execute(value.Id);
        }

        [RelayCommand]
        private async Task LoadArticle(int id)
        {
            var article = await _repo.GetByIdAsync(id, _currentUser.Roles);
            _currentArticle = article;
            ArticleText = article?.Text ?? string.Empty;
            ArticleTitle = article?.Name ?? string.Empty;
            ArticleDescription = article?.Description ?? string.Empty;
            SelectedArticleId = article?.Id ?? 0;

            ApproveCommand.NotifyCanExecuteChanged();
            RejectCommand.NotifyCanExecuteChanged();
            SaveCommand.NotifyCanExecuteChanged();
            CancelCommand.NotifyCanExecuteChanged();
        }

        [RelayCommand]
        private async Task LoadAllArticleSummaries()
        {
            var summaries = await _repo.GetAllSummariesAsync(_currentUser.Roles);
            ArticleSummaries.Clear();
            foreach (var summary in summaries)
                ArticleSummaries.Add(summary);
        }

        [RelayCommand(CanExecute = nameof(HasArticle))]
        private async Task Approve() => await SetStatusAsync("Accepted");

        [RelayCommand(CanExecute = nameof(HasArticle))]
        private async Task Reject() => await SetStatusAsync("Rejected");

        [RelayCommand(CanExecute = nameof(HasArticle))]
        private async Task Save()
        {
            if (_currentArticle is null)
                return;

            ApplyEditorFields(_currentArticle);

            await _repo.UpdateAsync(_currentArticle);
            await LoadAllArticleSummaries();
        }

        // Discards unsaved edits by reloading the article from the database.
        [RelayCommand(CanExecute = nameof(HasArticle))]
        private async Task Cancel()
        {
            if (SelectedArticleId is int id)
                await LoadArticle(id);
        }

        private async Task SetStatusAsync(string status)
        {
            if (_currentArticle is null)
                return;

            ApplyEditorFields(_currentArticle);
            _currentArticle.Status = status;

            await _repo.UpdateAsync(_currentArticle);
            await LoadAllArticleSummaries();
        }

        private void ApplyEditorFields(Article article)
        {
            article.Name = ArticleTitle;
            article.Description = ArticleDescription;
            article.Text = ArticleText;
        }
    }
}
