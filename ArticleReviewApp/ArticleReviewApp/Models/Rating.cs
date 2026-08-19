using System;
using System.Collections.Generic;

namespace ArticleReviewApp.Models;

public partial class Rating
{
    public int UserId { get; set; }

    public int ArticleId { get; set; }

    public int Rating1 { get; set; }

    public DateTime Timestamp { get; set; }

    public virtual Article Article { get; set; } = null!;

    public virtual User User { get; set; } = null!;
}
