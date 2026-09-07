using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ArticleReviewApp.Models.Dtos
{
    public partial class ArticleSummary
    {
        public int Id { get; set; }
        public string Name { get; set; } = null!;
        public string AuthorName { get; set; } = null!;
        public DateTime LastEdit { get; set; }
        public string Status { get; set; } = null!;
    }
}