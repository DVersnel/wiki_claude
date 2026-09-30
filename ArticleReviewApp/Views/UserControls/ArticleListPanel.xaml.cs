using System.Collections.ObjectModel;
using System.IdentityModel.Tokens.Jwt;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using ArticleReviewApp.Models;
using ArticleReviewApp.Models.Dtos;
using Microsoft.EntityFrameworkCore.Metadata.Internal;
using System.ComponentModel;

namespace ArticleReviewApp.Views.UserControls
{
    public partial class ArticleListPanel : UserControl
    {
        public ArticleListPanel()
        {
            InitializeComponent();

        }

        private async void ArticleListView_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if (ArticleListView.SelectedItem is ArticleSummary selected && DataContext is MainWindowViewModel vm)
            {
                try
                {
                    await vm.LoadArticle(selected.Id);
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.ToString(), "Failed to load article");
                }
            }
        }

        private string? _lastSortProperty;
        private ListSortDirection _lastSortDirection;

        private void ArticleListView_Sort(object sender, RoutedEventArgs e)
        {
            if (sender is not GridViewColumnHeader { Column.DisplayMemberBinding: Binding binding })
            {
                return;
            }

            string propertyName = binding.Path.Path;
            ListSortDirection direction = propertyName == _lastSortProperty && _lastSortDirection == ListSortDirection.Ascending
                ? ListSortDirection.Descending
                : ListSortDirection.Ascending;

            var view = CollectionViewSource.GetDefaultView(ArticleListView.ItemsSource);
            view.SortDescriptions.Clear();
            view.SortDescriptions.Add(new SortDescription(propertyName, direction));

            _lastSortProperty = propertyName;
            _lastSortDirection = direction;
        }
    }
}
