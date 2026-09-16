using System;
using System.Collections.Generic;

namespace ArticleReviewApp.Models;

public partial class Article
{
    public int Id { get; set; }

    public string Name { get; set; } = null!;

    public string Description { get; set; } = null!;

    public string Text { get; set; } = null!;

    public string Code { get; set; } = null!;

    public int UserId { get; set; }

    public string Status { get; set; } = null!;

    public DateTime LastEdit { get; set; }

    public virtual ICollection<Image> Images { get; set; } = new List<Image>();

    public virtual User User { get; set; } = null!;
}
