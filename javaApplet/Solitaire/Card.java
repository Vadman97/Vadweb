
public class Card
{
	private int rank;
	private int suit;
	private boolean faceDown;
	private String name;
	
	Card()
	{
		rank = 14;
		suit = 2;
		name = "ad.gif";
		faceDown = true; //change these to true later
	}
	Card(int r, int s)
	{
		rank = r;
		suit = s;
		name = "";
		faceDown = true; //change these to true later
		if (r <= 9 && r >= 2)
			name += r;
		else if (r <= 14)
		{
			if (r == 10)
				name += "t";
			if (r == 11)
				name += "j";
			if (r == 12)
				name += "q";
			if (r == 13)
				name += "k";
			if (r == 14)
				name += "a";
		}
		else
		{
			System.out.println("Wrong Value of Rank");
		}
		if (s == 1)
			name += "c";
		else if (s == 2)
			name += "d";
		else if (s == 3)
			name += "h";
		else if (s == 4)
			name += "s";
		else
		{
			System.out.println("Wrong Value of Suit");
		}
		name += ".gif";
	}
	public boolean isFaceDown()
	{
		if (faceDown)
			return true;
		else
			return false;
	}
	public void flip()
	{
		faceDown = !faceDown;
	}
	public int getRank()
	{
		return rank;
	}
	public int getSuit()
	{
		return suit;
	}
	public String getName()
	{
		return name;
	}

}
