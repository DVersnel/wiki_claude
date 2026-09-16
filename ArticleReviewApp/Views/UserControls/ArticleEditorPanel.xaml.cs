using System.Windows;
using System.Windows.Controls;

namespace ArticleReviewApp.Views.UserControls
{
    public partial class ArticleEditorPanel : UserControl
    {
        public ArticleEditorPanel()
        {
            InitializeComponent();
        }

        private async void ApproveButton_Click(object sender, RoutedEventArgs e)
        {
            if (DataContext is not MainWindowViewModel vm)
                return;

            try
            {
                await vm.ApproveArticleAsync();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.ToString(), "Failed to approve article");
            }
        }

        private async void DenyButton_Click(object sender, RoutedEventArgs e)
        {
            if (DataContext is not MainWindowViewModel vm)
                return;

            try
            {
                await vm.RejectArticleAsync();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.ToString(), "Failed to reject article");
            }
        }

        private async void SaveButton_Click(object sender, RoutedEventArgs e)
        {
            if (DataContext is not MainWindowViewModel vm)
                return;

            try
            {
                await vm.SaveArticleAsync();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.ToString(), "Failed to save article");
            }
        }

        private async void CancelButton_Click(object sender, RoutedEventArgs e)
        {
            if (DataContext is not MainWindowViewModel vm)
                return;

            try
            {
                if (vm.SelectedArticleId is int id)
                    await vm.LoadArticle(id);
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.ToString(), "Failed to save article");
            }
        }
    }
}
