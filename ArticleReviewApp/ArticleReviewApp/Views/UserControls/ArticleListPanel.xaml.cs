using System.Windows.Controls;
using ArticleReviewApp.Models;

namespace ArticleReviewApp.Views.UserControls
{
    public partial class ArticleListPanel : UserControl
    {
        public ArticleListPanel()
        {
            InitializeComponent();
            ArticleListView.Items.Add(new Article(){Name = "k", User = new User(){Name = "i"}, LastEdit = new DateTime(2008, 5, 1, 8, 30, 52)});
        }
    }
}
