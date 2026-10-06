using System.ComponentModel;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;

namespace ArticleReviewApp.Views.UserControls
{
    public partial class ArticleListPanel : UserControl
    {
        public ArticleListPanel()
        {
            InitializeComponent();
        }

        // Column-header sorting is purely a view concern (it only reorders the
        // CollectionView over the view model's list), so it stays in code-behind.
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
