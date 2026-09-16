using System;
using System.Collections.Generic;

namespace ArticleReviewApp.Models;

public partial class Image
{
    public int Id { get; set; }

    public string Description { get; set; } = null!;

    public DateTime LastEdit { get; set; }

    public string Path { get; set; } = null!;

    public int ArticleId { get; set; }

    public int DisplayOrder { get; set; }

    public virtual Article Article { get; set; } = null!;
}
