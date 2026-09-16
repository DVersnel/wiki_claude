using System.Collections.ObjectModel;
using System.IdentityModel.Tokens.Jwt;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using ArticleReviewApp.Models;
using ArticleReviewApp.Models.Dtos;
using Microsoft.EntityFrameworkCore.Metadata.Internal;

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

        private async void ArticleListView_Sort(HeaderName, Direction)
        {
            var view = CollectionViewSource.GetDefaultView(ArticleListView.ItemsSource);
            view.SortDescriptions.Clear();
            view.SortDescriptions.Add(new SortDescription(HeaderName, Direction));
        }
    }
}
